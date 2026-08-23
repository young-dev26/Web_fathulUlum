<?php

namespace Tests\Feature;

use App\Models\ParentProfile;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_can_only_see_linked_children_and_payment_data(): void
    {
        $parent = ParentProfile::create([
            'email' => 'wali@example.test',
            'password' => 'password',
            'nama_lengkap' => 'Wali Uji',
            'hubungan' => 'Ayah',
        ]);
        $child = Student::create([
            'email' => 'anak@example.test',
            'password' => 'password',
            'nisn' => '0010000002',
            'qr_code_key' => 'qr-parent-1',
            'nama_lengkap' => 'Anak Terhubung',
            'unit' => 'mi',
            'kelas' => 4,
            'jenis_kelamin' => 'P',
            'status_aktif' => true,
        ]);
        $unlinkedChild = Student::create([
            'email' => 'anak-lain@example.test',
            'password' => 'password',
            'nisn' => '0010000003',
            'qr_code_key' => 'qr-parent-2',
            'nama_lengkap' => 'Anak Tidak Terhubung',
            'unit' => 'mts',
            'kelas' => 7,
            'jenis_kelamin' => 'L',
            'status_aktif' => true,
        ]);
        $parent->children()->attach($child);
        Payment::create(['student_id' => $child->id, 'bulan' => 8, 'tahun' => 2026, 'nominal' => 150000, 'status_bayar' => 'lunas']);

        $this->actingAs($parent);

        $this->get(route('dashboard.parent.children'))
            ->assertOk()
            ->assertSee('Anak Terhubung')
            ->assertSee('Lunas')
            ->assertDontSee('Anak Tidak Terhubung');
        $this->get(route('dashboard.students.index'))->assertForbidden();
    }

    public function test_parent_can_log_in_and_reach_their_portal(): void
    {
        ParentProfile::create([
            'email' => 'wali.login@example.test',
            'password' => 'password',
            'nama_lengkap' => 'Wali Login',
        ]);

        $this->post(route('auth.login'), [
            'email' => 'wali.login@example.test',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('dashboard.parent.children'))->assertOk();
    }
}