<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasEventTest extends TestCase
{
    use RefreshDatabase;

    private User $petugas;
    private User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->petugas = User::factory()->create([
            'name' => 'Petugas Pilketos',
            'email' => 'petugas@sekolah.sch.id',
            'role' => 'PETUGAS',
        ]);

        $this->superAdmin = User::factory()->create([
            'name' => 'Super Administrator',
            'email' => 'admin@sekolah.sch.id',
            'role' => 'SUPER_ADMIN',
        ]);
    }

    public function test_guest_cannot_access_petugas_events(): void
    {
        $response = $this->get(route('petugas.events.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_super_admin_cannot_access_petugas_events_directly(): void
    {
        $response = $this->actingAs($this->superAdmin, 'web')
            ->get(route('petugas.events.index'));

        $response->assertStatus(403);
    }

    public function test_petugas_can_view_events_index(): void
    {
        Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Pemilihan Ketua OSIS',
            'category' => 'KANDIDAT',
            'room_code' => 'OSIS26',
            'status' => 'PUBLISHED',
        ]);

        $response = $this->actingAs($this->petugas, 'web')
            ->get(route('petugas.events.index'));

        $response->assertStatus(200);
        $response->assertSee('Pemilihan Ketua OSIS');
        $response->assertSee('OSIS26');
        $response->assertSee('Panel Petugas');
    }

    public function test_petugas_can_create_event_and_is_redirected_to_candidates_page(): void
    {
        $response = $this->actingAs($this->petugas, 'web')
            ->post(route('petugas.events.store'), [
                'title' => 'Pemilihan Ketua MPK 2026',
                'category' => 'KANDIDAT',
                'room_code' => 'mpk2026',
                'status' => 'DRAFT',
                'description' => 'Gunakan hak suara Anda secara bijak.',
            ]);

        $event = Event::where('title', 'Pemilihan Ketua MPK 2026')->first();
        $this->assertNotNull($event);

        $response->assertRedirect(route('petugas.candidates.index', $event));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('events', [
            'title' => 'Pemilihan Ketua MPK 2026',
            'category' => 'KANDIDAT',
            'room_code' => 'MPK2026', // Normalized to uppercase
            'status' => 'DRAFT',
            'created_by' => $this->petugas->id,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->petugas->id,
            'action' => 'CREATE_EVENT',
        ]);
    }

    public function test_petugas_can_create_event_with_auto_generated_room_code(): void
    {
        $response = $this->actingAs($this->petugas, 'web')
            ->post(route('petugas.events.store'), [
                'title' => 'Polling Ekstrakurikuler Favorit',
                'category' => 'OPSI',
                'room_code' => '',
                'status' => 'DRAFT',
            ]);

        $event = Event::where('title', 'Polling Ekstrakurikuler Favorit')->first();
        $this->assertNotNull($event);
        $this->assertEquals(6, strlen($event->room_code));
        $this->assertEquals('OPSI', $event->category);
        $this->assertEquals('DRAFT', $event->status);

        $response->assertRedirect(route('petugas.candidates.index', $event));
    }

    public function test_petugas_cannot_publish_event_with_less_than_two_candidates(): void
    {
        $event = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Hanya 1 Kandidat',
            'status' => 'DRAFT',
        ]);

        // Coba publish saat 0 kandidat
        $response = $this->actingAs($this->petugas, 'web')
            ->patch(route('petugas.events.status', $event), [
                'status' => 'PUBLISHED',
            ]);

        $response->assertSessionHas('error');
        $event->refresh();
        $this->assertEquals('DRAFT', $event->status);
    }

    public function test_petugas_can_publish_event_with_at_least_two_candidates(): void
    {
        $event = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Dengan 2 Kandidat',
            'status' => 'DRAFT',
        ]);

        $event->candidates()->createMany([
            ['candidate_number' => 1, 'name' => 'Paslon 1'],
            ['candidate_number' => 2, 'name' => 'Paslon 2'],
        ]);

        $response = $this->actingAs($this->petugas, 'web')
            ->patch(route('petugas.events.status', $event), [
                'status' => 'PUBLISHED',
            ]);

        $response->assertSessionHas('success');
        $event->refresh();
        $this->assertEquals('PUBLISHED', $event->status);
    }

    public function test_petugas_can_update_event(): void
    {
        $event = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Judul Awal',
            'category' => 'KANDIDAT',
            'room_code' => 'AWAL01',
            'status' => 'DRAFT',
        ]);

        // Berikan 2 kandidat agar syarat publikasi terpenuhi
        $event->candidates()->createMany([
            ['candidate_number' => 1, 'name' => 'Paslon 1'],
            ['candidate_number' => 2, 'name' => 'Paslon 2'],
        ]);

        $response = $this->actingAs($this->petugas, 'web')
            ->put(route('petugas.events.update', $event), [
                'title' => 'Event Judul Baru',
                'category' => 'OPSI',
                'room_code' => 'baru01',
                'status' => 'PUBLISHED',
                'description' => 'Deskripsi yang diperbarui',
            ]);

        $response->assertRedirect(route('petugas.events.index'));
        $response->assertSessionHas('success');

        $event->refresh();
        $this->assertEquals('Event Judul Baru', $event->title);
        $this->assertEquals('BARU01', $event->room_code);
        $this->assertEquals('OPSI', $event->category);
        $this->assertEquals('PUBLISHED', $event->status);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->petugas->id,
            'action' => 'UPDATE_EVENT',
        ]);
    }

    public function test_petugas_can_quick_update_status(): void
    {
        $event = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Status Test',
            'status' => 'DRAFT',
        ]);

        $event->candidates()->createMany([
            ['candidate_number' => 1, 'name' => 'Paslon 1'],
            ['candidate_number' => 2, 'name' => 'Paslon 2'],
        ]);

        $response = $this->actingAs($this->petugas, 'web')
            ->patch(route('petugas.events.status', $event), [
                'status' => 'PUBLISHED',
            ]);

        $response->assertRedirect();
        $event->refresh();
        $this->assertEquals('PUBLISHED', $event->status);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->petugas->id,
            'action' => 'CHANGE_EVENT_STATUS',
        ]);
    }

    public function test_petugas_can_delete_event(): void
    {
        $event = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Event Akan Dihapus',
            'room_code' => 'HAPUS1',
        ]);

        $response = $this->actingAs($this->petugas, 'web')
            ->delete(route('petugas.events.destroy', $event));

        $response->assertRedirect(route('petugas.events.index'));
        $this->assertDatabaseMissing('events', ['id' => $event->id]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->petugas->id,
            'action' => 'DELETE_EVENT',
        ]);
    }
}
