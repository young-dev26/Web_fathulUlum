<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentProfile;
use App\Models\StaffTu;
use App\Models\Payment;
use App\Models\Grade;
use App\Models\AcademicYear;
use App\Models\AcademicTerm;
use App\Models\Rombel;
use App\Models\Subject;
use App\Models\SubjectAssignment;
use App\Models\ScheduleSlot;
use App\Models\Schedule;
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

        StaffTu::updateOrCreate(['nip' => '199001010001'], ['nama_lengkap' => 'Staff TU Fathul Ulum', 'email' => 'tu@fathululum.sch.id', 'password' => 'password', 'unit' => 'yayasan', 'jabatan' => 'Administrasi']);

        $teachers = [
            ['email' => 'gurumi@fathululum.sch.id', 'name' => 'Ahmad Fauzi', 'unit' => 'mi', 'subject' => 'Tematik'],
            ['email' => 'gurumts@fathululum.sch.id', 'name' => 'Siti Aminah', 'unit' => 'mts', 'subject' => 'Bahasa Indonesia'],
        ];

        foreach ($teachers as $teacherData) {
            $nip = '198500'.($teacherData['unit'] === 'mi' ? '01' : '02');
            Teacher::updateOrCreate(
                ['nip_nuptk' => $nip],
                ['nip' => $nip, 'email' => $teacherData['email'], 'nama_lengkap' => $teacherData['name'], 'password' => 'password', 'unit' => $teacherData['unit'], 'mata_pelajaran' => $teacherData['subject']]
            );
        }

        foreach (['mi' => 6, 'mts' => 7] as $unit => $kelas) {
            for ($index = 1; $index <= 5; $index++) {
                $studentName = ($unit === 'mi' ? 'Siswa MI ' : 'Siswa MTs ').$index;
                $student = Student::updateOrCreate(
                    ['nisn' => ($unit === 'mi' ? '001' : '002').str_pad((string) $index, 7, '0', STR_PAD_LEFT)],
                    ['nis' => ($unit === 'mi' ? 'MI' : 'MT').str_pad((string) $index, 4, '0', STR_PAD_LEFT), 'email' => 'siswa'.$unit.$index.'@fathululum.sch.id', 'password' => 'password', 'nik' => null, 'qr_code_key' => Str::uuid()->toString(), 'nama_lengkap' => $studentName, 'unit' => $unit, 'kelas' => $kelas, 'jenis_kelamin' => $index % 2 ? 'L' : 'P', 'status_aktif' => true]
                );

                Payment::updateOrCreate(['student_id' => $student->id, 'bulan' => now()->month, 'tahun' => now()->year], ['nominal' => 50000, 'status_bayar' => $index % 2 ? 'lunas' : 'belum_lunas']);
                Grade::updateOrCreate(['student_id' => $student->id, 'semester' => 1, 'tahun_ajaran' => '2025/2026', 'mata_pelajaran' => 'Pendidikan Agama'], ['nilai_pengetahuan' => 82 + $index, 'nilai_keterampilan' => 80 + $index]);
            }
        }

        $academicYear = AcademicYear::updateOrCreate(['name' => '2026/2027'], ['starts_at' => '2026-07-01', 'ends_at' => '2027-06-30', 'is_active' => true]);
        $term = AcademicTerm::updateOrCreate(['academic_year_id' => $academicYear->id, 'semester' => 1], ['starts_at' => '2026-07-01', 'ends_at' => '2026-12-31']);
        $subjects = [
            'mi' => [['code' => 'MI-PAI', 'name' => 'Pendidikan Agama'], ['code' => 'MI-TEMATIK', 'name' => 'Tematik']],
            'mts' => [['code' => 'MTS-BIND', 'name' => 'Bahasa Indonesia'], ['code' => 'MTS-MTK', 'name' => 'Matematika']],
        ];
        foreach (['mi' => 6, 'mts' => 7] as $unit => $kelas) {
            $rombel = Rombel::updateOrCreate(['academic_year_id' => $academicYear->id, 'unit' => $unit, 'name' => $kelas.'A'], ['grade_level' => $kelas, 'homeroom_teacher_id' => Teacher::where('unit', $unit)->value('id')]);
            $rombel->students()->sync(Student::where('unit', $unit)->where('kelas', $kelas)->pluck('id'));
            $teacherId = Teacher::where('unit', $unit)->value('id');
            foreach ($subjects[$unit] as $subjectData) {
                $subject = Subject::updateOrCreate(['code' => $subjectData['code']], ['name' => $subjectData['name'], 'unit' => $unit, 'is_active' => true]);
                $assignment = SubjectAssignment::updateOrCreate(['rombel_id' => $rombel->id, 'subject_id' => $subject->id, 'teacher_id' => $teacherId, 'academic_term_id' => $term->id]);
                $slot = ScheduleSlot::updateOrCreate(['day_of_week' => $unit === 'mi' ? 1 : 2, 'period_number' => $subject->id % 2 + 1], ['starts_at' => $subject->id % 2 ? '07:00' : '08:00', 'ends_at' => $subject->id % 2 ? '07:45' : '08:45']);
                Schedule::updateOrCreate(['subject_assignment_id' => $assignment->id, 'schedule_slot_id' => $slot->id], ['room' => $unit === 'mi' ? 'Ruang MI 1' : 'Ruang MTs 1', 'starts_on' => '2026-07-01', 'ends_on' => '2026-12-31', 'is_active' => true]);
            }
        }

        $parent = ParentProfile::updateOrCreate(
            ['email' => 'orangtua@fathululum.sch.id'],
            ['nama_lengkap' => 'Orang Tua Siswa', 'password' => 'password', 'hubungan' => 'Wali', 'telepon' => '081234567890', 'nomor_hp' => '081234567890']
        );
        $parent->children()->sync(Student::where('status_aktif', true)->limit(2)->pluck('id'));

        $admin->update(['name' => 'Admin Yayasan']);
    }
}
