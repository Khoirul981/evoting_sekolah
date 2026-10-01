<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\User;
use App\Models\Vote;
use App\Models\Voter;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasMonitoringTest extends TestCase
{
    use RefreshDatabase;

    protected User $petugas;
    protected User $superAdmin;
    protected Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->petugas = User::factory()->create([
            'role' => 'PETUGAS',
            'name' => 'Petugas Monitoring',
            'email' => 'petugas.monitor@sekolah.sch.id',
        ]);

        $this->superAdmin = User::factory()->create([
            'role' => 'SUPER_ADMIN',
            'name' => 'Admin Utama',
            'email' => 'admin@sekolah.sch.id',
        ]);

        $this->event = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Pemilihan Ketua OSIS 2026',
            'category' => 'KANDIDAT',
            'room_code' => 'OSIS26',
            'status' => 'PUBLISHED',
        ]);
    }

    public function test_guest_cannot_access_monitoring(): void
    {
        $response = $this->get(route('petugas.events.monitor', $this->event));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_super_admin_cannot_access_petugas_monitoring_directly(): void
    {
        $response = $this->actingAs($this->superAdmin, 'web')
            ->get(route('petugas.events.monitor', $this->event));

        $response->assertStatus(403);
    }

    public function test_petugas_can_view_monitoring_page(): void
    {
        $candidate1 = $this->event->candidates()->create([
            'candidate_number' => 1,
            'name' => 'Ahmad Rizki & Maya Safitri',
        ]);

        $candidate2 = $this->event->candidates()->create([
            'candidate_number' => 2,
            'name' => 'Bima Sena & Citra Kirana',
        ]);

        $voter1 = Voter::factory()->create(['is_active' => true]);
        $voter2 = Voter::factory()->create(['is_active' => true]);

        // Simulasikan vote masuk untuk candidate 1
        Vote::create([
            'event_id' => $this->event->id,
            'candidate_id' => $candidate1->id,
        ]);

        EventParticipation::create([
            'event_id' => $this->event->id,
            'voter_id' => $voter1->id,
            'voted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->petugas, 'web')
            ->get(route('petugas.events.monitor', $this->event));

        $response->assertStatus(200);
        $response->assertSee('Pemilihan Ketua OSIS 2026');
        $response->assertSee('OSIS26');
        $response->assertSee('Ahmad Rizki & Maya Safitri');
        $response->assertSee('Bima Sena & Citra Kirana');
        $response->assertSee('Export Rekapitulasi (CSV)');
    }

    public function test_petugas_can_fetch_live_monitoring_data_json(): void
    {
        $candidate1 = $this->event->candidates()->create([
            'candidate_number' => 1,
            'name' => 'Paslon Satu',
        ]);

        $candidate2 = $this->event->candidates()->create([
            'candidate_number' => 2,
            'name' => 'Paslon Dua',
        ]);

        $voter = Voter::factory()->create(['name' => 'Budi Santoso', 'identifier' => '1001', 'is_active' => true]);

        Vote::create([
            'event_id' => $this->event->id,
            'candidate_id' => $candidate1->id,
        ]);

        EventParticipation::create([
            'event_id' => $this->event->id,
            'voter_id' => $voter->id,
            'voted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->petugas, 'web')
            ->getJson(route('petugas.events.monitor.data', $this->event));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'event_status',
            'total_voters',
            'total_votes',
            'participation_rate',
            'candidates' => [
                '*' => [
                    'id',
                    'candidate_number',
                    'name',
                    'votes_count',
                    'percentage',
                ],
            ],
            'recent_voters' => [
                '*' => [
                    'voter_name',
                    'identifier',
                    'type',
                    'voted_at',
                    'voted_at_human',
                ],
            ],
            'server_time',
        ]);

        $response->assertJson([
            'status' => 'success',
            'total_votes' => 1,
            'total_voters' => 1,
            'participation_rate' => 100,
        ]);
    }

    public function test_petugas_can_export_rekapitulasi_csv(): void
    {
        $candidate = $this->event->candidates()->create([
            'candidate_number' => 1,
            'name' => 'Paslon Satu',
        ]);

        $voter = Voter::factory()->create([
            'name' => 'Siswa Teladan',
            'identifier' => '9999',
            'type' => 'SISWA',
            'is_active' => true,
        ]);

        Vote::create([
            'event_id' => $this->event->id,
            'candidate_id' => $candidate->id,
        ]);

        EventParticipation::create([
            'event_id' => $this->event->id,
            'voter_id' => $voter->id,
            'voted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->petugas, 'web')
            ->get(route('petugas.events.export', $this->event));

        $response->assertStatus(200);
        $this->assertEquals('text/csv; charset=UTF-8', $response->headers->get('Content-Type'));
        $this->assertTrue(str_contains((string) $response->headers->get('Content-Disposition'), 'rekapitulasi_'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->petugas->id,
            'action' => 'EXPORT_REKAPITULASI',
        ]);
    }
}
