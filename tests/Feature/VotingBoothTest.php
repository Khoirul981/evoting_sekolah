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
            ]);

        $response->assertRedirect(route('voter.events.success', $this->event->room_code));
        $response->assertSessionHas('success');

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

    public function test_voter_cannot_vote_twice_on_same_event(): void
    {
        // Beri suara pertama kali
        $this->actingAs($this->voter, 'voter')
            ->post(route('voter.events.vote.post', $this->event->room_code), [
                'candidate_id' => $this->candidate1->id,
            ]);

        $this->assertEquals(1, Vote::count());

        // Coba beri suara kedua kali
        $response = $this->actingAs($this->voter, 'voter')
            ->post(route('voter.events.vote.post', $this->event->room_code), [
                'candidate_id' => $this->candidate2->id,
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
}
