<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentProfile;
use App\Models\Payment;
use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(['email' => 'admin@fathululum.sch.id'], ['name' => 'Admin Yayasan', 'password' => 'password', 'role' => 'admin', 'unit' => 'yayasan']);

        $teachers = [
            ['email' => 'gurumi@fathululum.sch.id', 'name' => 'Ahmad Fauzi', 'unit' => 'mi', 'subject' => 'Tematik'],
            ['email' => 'gurumts@fathululum.sch.id', 'name' => 'Siti Aminah', 'unit' => 'mts', 'subject' => 'Bahasa Indonesia'],
        ];

        foreach ($teachers as $teacherData) {
            Teacher::updateOrCreate(
                ['nip_nuptk' => '198500'.($teacherData['unit'] === 'mi' ? '01' : '02')],
                ['email' => $teacherData['email'], 'nama_lengkap' => $teacherData['name'], 'password' => 'password', 'unit' => $teacherData['unit'], 'mata_pelajaran' => $teacherData['subject']]
            );
        }

        foreach (['mi' => 6, 'mts' => 7] as $unit => $kelas) {
            for ($index = 1; $index <= 5; $index++) {
                $studentName = ($unit === 'mi' ? 'Siswa MI ' : 'Siswa MTs ').$index;
                $student = Student::updateOrCreate(
                    ['nisn' => ($unit === 'mi' ? '001' : '002').str_pad((string) $index, 7, '0', STR_PAD_LEFT)],
                    ['email' => 'siswa'.$unit.$index.'@fathululum.sch.id', 'password' => 'password', 'nik' => null, 'qr_code_key' => Str::uuid()->toString(), 'nama_lengkap' => $studentName, 'unit' => $unit, 'kelas' => $kelas, 'jenis_kelamin' => $index % 2 ? 'L' : 'P', 'status_aktif' => true]
                );

                Payment::updateOrCreate(['student_id' => $student->id, 'bulan' => now()->month, 'tahun' => now()->year], ['nominal' => 50000, 'status_bayar' => $index % 2 ? 'lunas' : 'belum_lunas']);
                Grade::updateOrCreate(['student_id' => $student->id, 'semester' => 1, 'tahun_ajaran' => '2025/2026', 'mata_pelajaran' => 'Pendidikan Agama'], ['nilai_pengetahuan' => 82 + $index, 'nilai_keterampilan' => 80 + $index]);
            }
        }

        ParentProfile::updateOrCreate(
            ['email' => 'orangtua@fathululum.sch.id'],
            ['nama_lengkap' => 'Orang Tua Siswa', 'password' => 'password', 'hubungan' => 'Wali', 'telepon' => '081234567890']
        );

        $admin->update(['name' => 'Admin Yayasan']);
    }
}
