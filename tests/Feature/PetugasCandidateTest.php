<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PetugasCandidateTest extends TestCase
{
    use RefreshDatabase;

    private User $petugas;
    private User $superAdmin;
    private Event $event;

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

        $this->event = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Pemilihan Ketua OSIS 2026',
            'category' => 'KANDIDAT',
            'room_code' => 'OSIS26',
            'status' => 'DRAFT',
        ]);
    }

    public function test_guest_cannot_access_candidates_index(): void
    {
        $response = $this->get(route('petugas.candidates.index', $this->event));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_super_admin_cannot_access_petugas_candidates_directly(): void
    {
        $response = $this->actingAs($this->superAdmin, 'web')
            ->get(route('petugas.candidates.index', $this->event));

        $response->assertStatus(403);
    }

    public function test_petugas_can_view_candidates_page(): void
    {
        $candidate = $this->event->candidates()->create([
            'candidate_number' => 1,
            'name' => 'Ahmad Rizki & Maya Safitri',
            'vision' => 'Mewujudkan OSIS yang aktif, kreatif, dan berintegritas.',
            'mission' => "1. Menyelenggarakan kegiatan inovatif\n2. Meningkatkan solidaritas siswa",
        ]);

        $response = $this->actingAs($this->petugas, 'web')
            ->get(route('petugas.candidates.index', $this->event));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Rizki & Maya Safitri');
        $response->assertSee('#01');
        $response->assertSee('Pemilihan Ketua OSIS 2026');
        $response->assertSee('Syarat Minimal Belum Terpenuhi');
        $response->assertDontSee('Syarat Minimal Terpenuhi');

        // Tambah kandidat ke-2
        $this->event->candidates()->create([
            'candidate_number' => 2,
            'name' => 'Kandidat Kedua',
        ]);

        $response2 = $this->actingAs($this->petugas, 'web')
            ->get(route('petugas.candidates.index', $this->event));

        $response2->assertStatus(200);
        $response2->assertDontSee('Syarat Minimal Belum Terpenuhi');
        $response2->assertDontSee('Syarat Minimal Terpenuhi');
    }

    public function test_petugas_can_create_candidate_with_photo(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('paslon1.jpg', 600, 600);

        $response = $this->actingAs($this->petugas, 'web')
            ->post(route('petugas.candidates.store', $this->event), [
                'candidate_number' => 1,
                'name' => 'Bima Sena & Citra Kirana',
                'vision' => 'Visi paslon satu yang berdaya saing global.',
                'mission' => '1. Program kerja unggulan sains dan seni.',
                'description' => 'Ketua: Kelas XI IPA 1, Wakil: Kelas X IPS 2',
                'photo' => $file,
            ]);

        $response->assertRedirect(route('petugas.candidates.index', $this->event));
        $response->assertSessionHas('success');

        $candidate = Candidate::where('event_id', $this->event->id)->first();
        $this->assertNotNull($candidate);
        $this->assertEquals(1, $candidate->candidate_number);
        $this->assertEquals('Bima Sena & Citra Kirana', $candidate->name);
        $this->assertNotNull($candidate->photo);

        $this->assertTrue(Storage::disk('public')->exists($candidate->photo));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->petugas->id,
            'action' => 'CREATE_CANDIDATE',
        ]);
    }

    public function test_petugas_cannot_create_candidate_with_duplicate_number_in_same_event(): void
    {
        $this->event->candidates()->create([
            'candidate_number' => 1,
            'name' => 'Kandidat Nomor Satu',
        ]);

        $response = $this->actingAs($this->petugas, 'web')
            ->post(route('petugas.candidates.store', $this->event), [
                'candidate_number' => 1,
                'name' => 'Kandidat Duplikat Nomor Satu',
            ]);

        $response->assertSessionHasErrors('candidate_number');
        $this->assertEquals(1, $this->event->candidates()->count());
    }

    public function test_petugas_can_use_same_candidate_number_in_different_event(): void
    {
        $otherEvent = Event::factory()->create([
            'created_by' => $this->petugas->id,
            'title' => 'Pemilihan Lain',
            'room_code' => 'LAIN01',
        ]);

        $otherEvent->candidates()->create([
            'candidate_number' => 1,
            'name' => 'Kandidat Event Lain',
        ]);

        $response = $this->actingAs($this->petugas, 'web')
            ->post(route('petugas.candidates.store', $this->event), [
                'candidate_number' => 1,
                'name' => 'Kandidat Event Ini',
            ]);

        $response->assertRedirect(route('petugas.candidates.index', $this->event));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('candidates', [
            'event_id' => $this->event->id,
            'candidate_number' => 1,
            'name' => 'Kandidat Event Ini',
        ]);
    }

    public function test_petugas_can_update_candidate_and_replace_photo(): void
    {
        Storage::fake('public');

        $oldFile = UploadedFile::fake()->image('old_photo.jpg');
        $oldPath = $oldFile->store('candidates', 'public');

        $candidate = $this->event->candidates()->create([
            'candidate_number' => 1,
            'name' => 'Nama Lama',
            'vision' => 'Visi Lama',
            'photo' => $oldPath,
        ]);

        $this->assertTrue(Storage::disk('public')->exists($oldPath));

        $newFile = UploadedFile::fake()->image('new_photo.png');

        $response = $this->actingAs($this->petugas, 'web')
            ->put(route('petugas.candidates.update', [$this->event, $candidate]), [
                'candidate_number' => 2,
                'name' => 'Nama Baru Yang Diperbarui',
                'vision' => 'Visi Baru',
                'mission' => 'Misi Baru',
                'photo' => $newFile,
            ]);

        $response->assertRedirect(route('petugas.candidates.index', $this->event));
        $response->assertSessionHas('success');

        $candidate->refresh();
        $this->assertEquals(2, $candidate->candidate_number);
        $this->assertEquals('Nama Baru Yang Diperbarui', $candidate->name);
        $this->assertEquals('Visi Baru', $candidate->vision);

        // Old photo must be deleted and replaced with new photo
        $this->assertFalse(Storage::disk('public')->exists($oldPath));
        $this->assertTrue(Storage::disk('public')->exists($candidate->photo));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->petugas->id,
            'action' => 'UPDATE_CANDIDATE',
        ]);
    }

    public function test_petugas_can_delete_candidate_and_remove_photo(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('delete_me.jpg');
        $path = $file->store('candidates', 'public');

        $candidate = $this->event->candidates()->create([
            'candidate_number' => 1,
            'name' => 'Kandidat Dihapus',
            'photo' => $path,
        ]);

        $this->assertTrue(Storage::disk('public')->exists($path));

        $response = $this->actingAs($this->petugas, 'web')
            ->delete(route('petugas.candidates.destroy', [$this->event, $candidate]));

        $response->assertRedirect(route('petugas.candidates.index', $this->event));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('candidates', ['id' => $candidate->id]);
        $this->assertFalse(Storage::disk('public')->exists($path));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->petugas->id,
            'action' => 'DELETE_CANDIDATE',
        ]);
    }
}
