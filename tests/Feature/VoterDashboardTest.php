<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\User;
use App\Models\Voter;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoterDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected Voter $voter;
    protected User $petugas;
    protected Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->voter = Voter::factory()->create([
            'name' => 'Ahmad Santoso',
            'identifier' => '100123',
            'type' => 'SISWA',
            'is_active' => true,
        ]);

        $this->petugas = User::factory()->create([
            'role' => 'PETUGAS',
            'email' => 'petugas.voter@sekolah.sch.id',
        ]);

        $this->event = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Pemilihan Ketua OSIS 2026',
            'category' => 'KANDIDAT',
            'room_code' => 'OSIS26',
            'status' => 'PUBLISHED',
        ]);

        // Berikan 2 kandidat valid
        Candidate::factory()->create([
            'event_id' => $this->event->id,
            'candidate_number' => 1,
            'name' => 'Paslon Satu',
        ]);

        Candidate::factory()->create([
            'event_id' => $this->event->id,
            'candidate_number' => 2,
            'name' => 'Paslon Dua',
        ]);
    }

    public function test_guest_cannot_access_voter_dashboard(): void
    {
        $response = $this->get(route('voter.dashboard'));
        $response->assertRedirect(route('voter.login'));
    }

    public function test_voter_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->voter, 'voter')
            ->get(route('voter.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Santoso');
        $response->assertSee('100123');
        $response->assertSee('SISWA');
        $response->assertSee('DPT Aktif');
        $response->assertSee('Ketik Kode Room');
        $response->assertSee('Masuk Bilik Suara');
    }

    public function test_voter_join_room_fails_if_code_invalid(): void
    {
        $response = $this->actingAs($this->voter, 'voter')
            ->post(route('voter.join.post'), [
                'room_code' => 'INVALID99',
            ]);

        $response->assertSessionHasErrors('room_code');
    }

    public function test_voter_join_room_fails_if_event_is_draft(): void
    {
        $draftEvent = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Draft',
            'room_code' => 'DRAFT1',
            'status' => 'DRAFT',
            'start_time' => null,
            'end_time' => null,
        ]);

        Candidate::factory()->count(2)->create(['event_id' => $draftEvent->id]);

        $response = $this->actingAs($this->voter, 'voter')
            ->post(route('voter.join.post'), [
                'room_code' => 'DRAFT1',
            ]);

        $response->assertSessionHasErrors('room_code');
    }

    public function test_voter_join_room_fails_if_event_is_closed(): void
    {
        $closedEvent = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Tutup',
            'room_code' => 'CLOSE1',
            'status' => 'CLOSED',
        ]);

        Candidate::factory()->count(2)->create(['event_id' => $closedEvent->id]);

        $response = $this->actingAs($this->voter, 'voter')
            ->post(route('voter.join.post'), [
                'room_code' => 'CLOSE1',
            ]);

        $response->assertSessionHasErrors('room_code');
    }

    public function test_voter_join_room_fails_if_event_has_less_than_two_candidates(): void
    {
        $emptyEvent = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Kurang Kandidat',
            'room_code' => 'EMPTY1',
            'status' => 'PUBLISHED',
        ]);

        Candidate::factory()->create(['event_id' => $emptyEvent->id]); // Cuma 1 kandidat

        $response = $this->actingAs($this->voter, 'voter')
            ->post(route('voter.join.post'), [
                'room_code' => 'EMPTY1',
            ]);

        $response->assertSessionHasErrors('room_code');
    }

    public function test_voter_join_room_fails_if_already_voted(): void
    {
        EventParticipation::create([
            'event_id' => $this->event->id,
            'voter_id' => $this->voter->id,
            'voted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->voter, 'voter')
            ->post(route('voter.join.post'), [
                'room_code' => 'OSIS26',
            ]);

        $response->assertSessionHasErrors('room_code');
    }

    public function test_voter_join_room_success_redirects_to_voting_booth(): void
    {
        $response = $this->actingAs($this->voter, 'voter')
            ->post(route('voter.join.post'), [
                'room_code' => 'osis26', // lowercase should be normalized to uppercase
            ]);

        $response->assertRedirect(route('voter.events.vote', 'OSIS26'));
    }
}
