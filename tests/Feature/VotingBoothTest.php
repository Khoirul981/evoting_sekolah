<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\User;
use App\Models\Vote;
use App\Models\Voter;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VotingBoothTest extends TestCase
{
    use RefreshDatabase;

    protected User $petugas;
    protected Voter $voter;
    protected Event $event;
    protected Candidate $candidate1;
    protected Candidate $candidate2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->petugas = User::factory()->create([
            'role' => 'PETUGAS',
        ]);

        $this->voter = Voter::factory()->create([
            'identifier' => '100123',
            'name' => 'Ahmad Santoso',
            'type' => 'SISWA',
        ]);

        $this->event = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Pemilihan Ketua OSIS 2026',
            'category' => 'KANDIDAT',
            'room_code' => 'OSIS26',
            'status' => 'PUBLISHED',
            'start_time' => Carbon::now()->subHour(),
            'end_time' => Carbon::now()->addHours(2),
        ]);

        $this->candidate1 = Candidate::factory()->create([
            'event_id' => $this->event->id,
            'candidate_number' => 1,
            'name' => 'Paslon Ahmad & Budi',
            'vision' => 'Mewujudkan OSIS yang unggul dan inovatif.',
            'mission' => '1. Mengadakan kegiatan kreatif.\n2. Menjaga solidaritas antar siswa.',
        ]);

        $this->candidate2 = Candidate::factory()->create([
            'event_id' => $this->event->id,
            'candidate_number' => 2,
            'name' => 'Paslon Citra & Dewi',
            'vision' => 'Membangun karakter siswa berintegritas.',
            'mission' => '1. Program pembinaan kepemimpinan.\n2. Literasi digital.',
        ]);
    }

    public function test_guest_cannot_access_voting_booth(): void
    {
        $response = $this->get(route('voter.events.vote', $this->event->room_code));
        $response->assertRedirect(route('voter.login'));
    }

    public function test_voter_can_view_voting_booth_with_candidates(): void
    {
        $response = $this->actingAs($this->voter, 'voter')
            ->get(route('voter.events.vote', 'osis26')); // lowercase should be normalized

        $response->assertStatus(200);
        $response->assertSee('Pemilihan Ketua OSIS 2026');
        $response->assertSee('OSIS26');
        $response->assertSee('Paslon Ahmad & Budi');
        $response->assertSee('Paslon Citra & Dewi');
        $response->assertSee('COBLOS KANDIDAT');
    }

    public function test_voter_cannot_view_voting_booth_for_draft_event(): void
    {
        $draftEvent = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Masih Draft',
            'room_code' => 'DRAFT01',
            'status' => 'DRAFT',
            'start_time' => null,
            'end_time' => null,
        ]);
        Candidate::factory()->count(2)->create(['event_id' => $draftEvent->id]);

        $response = $this->actingAs($this->voter, 'voter')
            ->get(route('voter.events.vote', 'DRAFT01'));

        $response->assertRedirect(route('voter.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_voter_cannot_view_voting_booth_for_closed_event(): void
    {
        $closedEvent = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Sudah Tutup',
            'room_code' => 'CLOSED1',
            'status' => 'CLOSED',
        ]);
        Candidate::factory()->count(2)->create(['event_id' => $closedEvent->id]);

        $response = $this->actingAs($this->voter, 'voter')
            ->get(route('voter.events.vote', 'CLOSED1'));

        $response->assertRedirect(route('voter.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_voter_who_already_voted_is_redirected_to_success_page(): void
    {
        EventParticipation::create([
            'event_id' => $this->event->id,
            'voter_id' => $this->voter->id,
            'voted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->voter, 'voter')
            ->get(route('voter.events.vote', $this->event->room_code));

        $response->assertRedirect(route('voter.events.success', $this->event->room_code));
    }

    public function test_voter_can_cast_vote_successfully_and_preserves_secrecy(): void
    {
        $this->assertEquals(0, Vote::count());
        $this->assertEquals(0, EventParticipation::where('event_id', $this->event->id)->whereNotNull('voted_at')->count());

        $response = $this->actingAs($this->voter, 'voter')
            ->post(route('voter.events.vote.post', $this->event->room_code), [
                'candidate_id' => $this->candidate1->id,
                'room_code' => $this->event->room_code,
            ]);

        $response->assertRedirect(route('voter.events.success', $this->event->room_code));

        // 1. Suara tercatat di tabel votes
        $this->assertEquals(1, Vote::where('event_id', $this->event->id)->where('candidate_id', $this->candidate1->id)->count());

        // 2. Partisipasi pemilih terdata (anti double-voting)
        $participation = EventParticipation::where('event_id', $this->event->id)
            ->where('voter_id', $this->voter->id)
            ->first();

        $this->assertNotNull($participation);
        $this->assertNotNull($participation->voted_at);

        // 3. Audit Log terbuat
        $this->assertTrue(AuditLog::where('action', 'vote.cast')->exists());
    }

    public function test_voter_cannot_vote_with_invalid_room_code(): void
    {
        $response = $this->actingAs($this->voter, 'voter')
            ->post(route('voter.events.vote.post', $this->event->room_code), [
                'candidate_id' => $this->candidate1->id,
                'room_code' => 'WRONGCODE',
            ]);

        $response->assertSessionHas('error', 'Kode ruangan yang Anda masukkan tidak sesuai dengan event pemilihan ini.');
        $this->assertEquals(0, Vote::count());
    }

    public function test_voter_cannot_vote_twice_on_same_event(): void
    {
        // Beri suara pertama kali
        $this->actingAs($this->voter, 'voter')
            ->post(route('voter.events.vote.post', $this->event->room_code), [
                'candidate_id' => $this->candidate1->id,
                'room_code' => $this->event->room_code,
            ]);

        $this->assertEquals(1, Vote::count());

        // Coba beri suara kedua kali
        $response = $this->actingAs($this->voter, 'voter')
            ->post(route('voter.events.vote.post', $this->event->room_code), [
                'candidate_id' => $this->candidate2->id,
                'room_code' => $this->event->room_code,
            ]);

        $response->assertRedirect(route('voter.events.success', $this->event->room_code));

        // Jumlah suara tetap 1 (tidak bertambah)
        $this->assertEquals(1, Vote::count());
    }

    public function test_voter_cannot_vote_for_candidate_from_another_event(): void
    {
        $anotherEvent = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Lain',
            'room_code' => 'OTHER01',
            'status' => 'PUBLISHED',
        ]);
        $otherCandidate = Candidate::factory()->create([
            'event_id' => $anotherEvent->id,
            'candidate_number' => 1,
            'name' => 'Kandidat Event Lain',
        ]);

        $response = $this->actingAs($this->voter, 'voter')
            ->post(route('voter.events.vote.post', $this->event->room_code), [
                'candidate_id' => $otherCandidate->id,
                'room_code' => $this->event->room_code,
            ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, Vote::count());
    }

    public function test_voter_can_view_success_receipt_page(): void
    {
        EventParticipation::create([
            'event_id' => $this->event->id,
            'voter_id' => $this->voter->id,
            'voted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->voter, 'voter')
            ->get(route('voter.events.success', $this->event->room_code));

        $response->assertStatus(200);
        $response->assertSee('Terima Kasih Atas Partisipasi Anda!');
        $response->assertSee('Ahmad Santoso');
        $response->assertSee('100123');
        $response->assertSee('OSIS26');
    }

    public function test_voter_who_has_not_voted_cannot_view_success_page(): void
    {
        $response = $this->actingAs($this->voter, 'voter')
            ->get(route('voter.events.success', $this->event->room_code));

        $response->assertRedirect(route('voter.events.vote', $this->event->room_code));
    }

    public function test_voter_dashboard_shows_all_published_ongoing_events_from_all_petugas(): void
    {
        $petugas2 = User::factory()->create([
            'name' => 'Siti Aminah Petugas MPK',
            'role' => 'PETUGAS',
        ]);

        $event2 = Event::factory()->create([
            'created_by' => $petugas2->id,
            'title' => 'Pemilihan Ketua MPK 2026',
            'category' => 'KANDIDAT',
            'room_code' => 'MPK26',
            'status' => 'PUBLISHED',
            'start_time' => Carbon::now()->subHour(),
            'end_time' => Carbon::now()->addHours(3),
        ]);
        Candidate::factory()->count(2)->create(['event_id' => $event2->id]);

        $response = $this->actingAs($this->voter, 'voter')
            ->get(route('voter.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Pemilihan Ketua OSIS 2026');
        $response->assertSee('Pemilihan Ketua MPK 2026');
        $response->assertSee('Siti Aminah Petugas MPK');
        $response->assertSee('Daftar Event Pemilihan Sedang Berlangsung');
        $response->assertSee('Sedang Berlangsung');
        $response->assertSee('Detail Event');
    }

    public function test_voter_dashboard_does_not_show_draft_closed_or_future_events(): void
    {
        $draftEvent = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Rahasia DRAFT',
            'room_code' => 'DRAFT99',
            'status' => 'DRAFT',
            'start_time' => null,
            'end_time' => null,
        ]);

        $closedEvent = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Lampau CLOSED',
            'room_code' => 'CLOSED99',
            'status' => 'CLOSED',
        ]);

        $futureEvent = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Masa Depan FUTURE',
            'room_code' => 'FUTUR99',
            'status' => 'PUBLISHED',
            'start_time' => Carbon::now()->addDays(2),
            'end_time' => Carbon::now()->addDays(3),
        ]);

        $response = $this->actingAs($this->voter, 'voter')
            ->get(route('voter.dashboard'));

        $response->assertStatus(200);
        $response->assertDontSee('Event Rahasia DRAFT');
        $response->assertDontSee('Event Lampau CLOSED');
        $response->assertDontSee('Event Masa Depan FUTURE');
    }

    public function test_voter_can_join_event_with_valid_event_id_and_matching_room_code(): void
    {
        $response = $this->actingAs($this->voter, 'voter')
            ->post(route('voter.join.post'), [
                'event_id' => $this->event->id,
                'room_code' => 'OSIS26',
            ]);

        $response->assertRedirect(route('voter.events.vote', 'OSIS26'));
    }

    public function test_voter_cannot_join_event_with_mismatched_room_code_for_that_event(): void
    {
        $response = $this->actingAs($this->voter, 'voter')
            ->from(route('voter.dashboard'))
            ->post(route('voter.join.post'), [
                'event_id' => $this->event->id,
                'room_code' => 'KODESALAH',
            ]);

        $response->assertRedirect(route('voter.dashboard'));
        $response->assertSessionHasErrors(['room_code_' . $this->event->id, 'room_code']);
        $response->assertSessionHas('error');
    }

    public function test_voter_dashboard_shows_already_voted_badge_and_link(): void
    {
        EventParticipation::create([
            'event_id' => $this->event->id,
            'voter_id' => $this->voter->id,
            'voted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->voter, 'voter')
            ->get(route('voter.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Sudah Memilih');
        $response->assertSee('Hak Suara Telah Digunakan');
        $response->assertSee(route('voter.events.success', $this->event->room_code));
        $response->assertSee(route('voter.events.monitoring', $this->event->room_code));
        $response->assertSee('Live Perolehan Suara');
    }

    public function test_voter_who_has_voted_can_view_event_monitoring(): void
    {
        Vote::create([
            'event_id' => $this->event->id,
            'candidate_id' => $this->candidate1->id,
        ]);

        EventParticipation::create([
            'event_id' => $this->event->id,
            'voter_id' => $this->voter->id,
            'voted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->voter, 'voter')
            ->get(route('voter.events.monitoring', $this->event->room_code));

        $response->assertStatus(200);
        $response->assertSee('Live Perolehan Suara');
        $response->assertSee('Pemilihan Ketua OSIS 2026');
        $response->assertSee('Paslon Ahmad & Budi');
        $response->assertSee('Paslon Citra & Dewi');
        $response->assertSee('Total Suara Masuk');
    }

    public function test_voter_who_has_not_voted_can_view_event_monitoring(): void
    {
        $response = $this->actingAs($this->voter, 'voter')
            ->get(route('voter.events.monitoring', $this->event->room_code));

        $response->assertStatus(200);
        $response->assertSee('Live Perolehan Suara');
        $response->assertSee('BELUM MEMILIH');
        $response->assertSee('Masuk Bilik Suara');
    }

    public function test_voter_who_has_voted_can_poll_monitoring_data_api(): void
    {
        Vote::create([
            'event_id' => $this->event->id,
            'candidate_id' => $this->candidate1->id,
        ]);

        EventParticipation::create([
            'event_id' => $this->event->id,
            'voter_id' => $this->voter->id,
            'voted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->voter, 'voter')
            ->getJson(route('voter.events.monitoring.data', $this->event->room_code));

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'total_votes' => 1,
        ]);
        $response->assertJsonStructure([
            'status',
            'event_status',
            'total_voters',
            'total_votes',
            'participation_rate',
            'candidates',
            'is_tied',
            'leading_candidates',
            'recent_voters',
            'server_time',
        ]);
    }

    public function test_voter_who_has_not_voted_can_also_poll_monitoring_data_api(): void
    {
        $response = $this->actingAs($this->voter, 'voter')
            ->getJson(route('voter.events.monitoring.data', $this->event->room_code));

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'total_votes' => 0,
        ]);
    }
}
