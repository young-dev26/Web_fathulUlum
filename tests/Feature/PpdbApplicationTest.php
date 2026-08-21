<?php

namespace Tests\Feature;

use App\Models\PpdbApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PpdbApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_visitor_can_submit_ppdb_application(): void
    {
        $response = $this->post(route('ppdb.register.store'), [
            'nama_lengkap' => 'Calon Siswa Baru',
            'nisn' => '1234567890',
            'nama_wali' => 'Wali Calon Siswa',
            'email' => 'wali@example.test',
            'telepon' => '081234567890',
            'unit' => 'mi',
            'kelas_tujuan' => 1,
            'alamat' => 'Poteran, Talango, Sumenep',
        ]);

        $response->assertOk()->assertViewIs('ppdb.success');
        $this->assertDatabaseHas('ppdb_applications', [
            'nama_lengkap' => 'Calon Siswa Baru',
            'unit' => 'mi',
            'status' => 'baru',
        ]);
        $this->assertNotEmpty(PpdbApplication::first()->nomor_pendaftaran);
    }
}
