<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\ParentProfile;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\AcademicYear;
use App\Models\AcademicTerm;
use App\Models\Rombel;
use App\Models\Subject;
use App\Models\SubjectAssignment;
use App\Models\ScheduleSlot;
use App\Models\Schedule;
use App\Models\ScheduleSession;
use App\Models\LessonAttendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_enter_grade_only_for_their_unit_and_student_can_view_it(): void
    {
        $teacher = Teacher::create(['password' => 'password', 'nip_nuptk' => '19850010', 'nama_lengkap' => 'Guru MI', 'email' => 'guru@uji.test', 'unit' => 'mi']);
        $student = Student::create(['password' => 'password', 'nis' => 'MI0001', 'nisn' => '0010000010', 'qr_code_key' => 'academic-qr', 'nama_lengkap' => 'Siswa MI', 'email' => 'siswa@uji.test', 'unit' => 'mi', 'kelas' => 6, 'jenis_kelamin' => 'L', 'status_aktif' => true]);
        $otherUnitStudent = Student::create(['password' => 'password', 'nis' => 'MT0001', 'nisn' => '0020000010', 'qr_code_key' => 'academic-qr-2', 'nama_lengkap' => 'Siswa MTs', 'email' => 'siswa2@uji.test', 'unit' => 'mts', 'kelas' => 7, 'jenis_kelamin' => 'P', 'status_aktif' => true]);

        $this->actingAs($teacher)->post(route('dashboard.grades.store'), ['student_id' => $otherUnitStudent->id, 'semester' => 1, 'tahun_ajaran' => '2026/2027', 'mata_pelajaran' => 'Matematika', 'nilai_pengetahuan' => 90])->assertForbidden();
        $this->post(route('dashboard.grades.store'), ['student_id' => $student->id, 'semester' => 1, 'tahun_ajaran' => '2026/2027', 'mata_pelajaran' => 'Matematika', 'nilai_pengetahuan' => 90, 'nilai_keterampilan' => 88])->assertRedirect();
        $this->assertDatabaseHas('grades', ['student_id' => $student->id, 'mata_pelajaran' => 'Matematika', 'nilai_pengetahuan' => 90]);

        $this->actingAs($student)->get(route('dashboard.grades.index'))->assertOk()->assertSee('Matematika')->assertSee('90');
    }

    public function test_approved_leave_creates_attendance_for_each_day_and_cannot_be_reopened(): void
    {
        $student = Student::create(['password' => 'password', 'nisn' => '0030000010', 'qr_code_key' => 'leave-qr', 'nama_lengkap' => 'Siswa Izin', 'email' => 'leave@uji.test', 'unit' => 'mi', 'kelas' => 6, 'jenis_kelamin' => 'L', 'status_aktif' => true]);
        $teacher = Teacher::create(['password' => 'password', 'nip_nuptk' => '19850011', 'nama_lengkap' => 'Guru Izin', 'email' => 'guru-izin@uji.test', 'unit' => 'mi']);
        $this->actingAs($student)->post(route('dashboard.student.leave-requests.store'), ['jenis_izin' => 'sakit', 'tanggal_mulai' => '2026-08-21', 'tanggal_selesai' => '2026-08-22', 'keterangan' => 'Demam'])->assertRedirect();
        $request = $student->leaveRequests()->first();

        $this->actingAs($teacher)->post(route('dashboard.leave-requests.review', $request), ['action' => 'approve'])->assertRedirect();
        $this->assertSame(2, Attendance::where('student_id', $student->id)->where('status', 'Sakit')->count());
        $this->post(route('dashboard.leave-requests.review', $request), ['action' => 'reject'])->assertStatus(422);
    }

    public function test_seeded_parent_relationship_is_explicitly_supported(): void
    {
        $parent = ParentProfile::create(['email' => 'parent@uji.test', 'password' => 'password', 'nama_lengkap' => 'Wali Uji', 'nomor_hp' => '0811111111']);
        $student = Student::create(['password' => 'password', 'nisn' => '0040000010', 'qr_code_key' => 'parent-qr', 'nama_lengkap' => 'Anak Uji', 'email' => 'anak@uji.test', 'unit' => 'mi', 'kelas' => 4, 'jenis_kelamin' => 'P', 'status_aktif' => true]);
        $parent->children()->attach($student);

        $this->actingAs($parent)->get(route('dashboard.grades.index'))->assertOk()->assertSee('Anak Uji');
    }

    public function test_two_lesson_sessions_on_one_day_keep_separate_attendance(): void
    {
        $teacher = Teacher::create(['password' => 'password', 'nip_nuptk' => '19850020', 'nama_lengkap' => 'Guru Jadwal', 'email' => 'jadwal@uji.test', 'unit' => 'mi']);
        $student = Student::create(['password' => 'password', 'nisn' => '0050000010', 'qr_code_key' => 'schedule-qr', 'nama_lengkap' => 'Siswa Jadwal', 'email' => 'jadwal-siswa@uji.test', 'unit' => 'mi', 'kelas' => 6, 'jenis_kelamin' => 'L', 'status_aktif' => true]);
        $year = AcademicYear::create(['name' => '2026/2027', 'starts_at' => '2026-07-01', 'ends_at' => '2027-06-30', 'is_active' => true]);
        $term = AcademicTerm::create(['academic_year_id' => $year->id, 'semester' => 1, 'starts_at' => '2026-07-01', 'ends_at' => '2026-12-31']);
        $rombel = Rombel::create(['academic_year_id' => $year->id, 'unit' => 'mi', 'grade_level' => 6, 'name' => '6A']);
        $rombel->students()->attach($student);
        $subject = Subject::create(['code' => 'UJI-MTK', 'name' => 'Matematika', 'unit' => 'mi']);
        $assignment = SubjectAssignment::create(['rombel_id' => $rombel->id, 'subject_id' => $subject->id, 'teacher_id' => $teacher->id, 'academic_term_id' => $term->id]);
        $slotOne = ScheduleSlot::create(['day_of_week' => 1, 'period_number' => 1, 'starts_at' => '07:00', 'ends_at' => '07:45']);
        $slotTwo = ScheduleSlot::create(['day_of_week' => 1, 'period_number' => 2, 'starts_at' => '08:00', 'ends_at' => '08:45']);
        $scheduleOne = Schedule::create(['subject_assignment_id' => $assignment->id, 'schedule_slot_id' => $slotOne->id]);
        $scheduleTwo = Schedule::create(['subject_assignment_id' => $assignment->id, 'schedule_slot_id' => $slotTwo->id]);
        $sessionOne = ScheduleSession::create(['schedule_id' => $scheduleOne->id, 'session_date' => '2026-08-24']);
        $sessionTwo = ScheduleSession::create(['schedule_id' => $scheduleTwo->id, 'session_date' => '2026-08-24']);

        LessonAttendance::create(['schedule_session_id' => $sessionOne->id, 'student_id' => $student->id, 'status' => 'Hadir']);
        LessonAttendance::create(['schedule_session_id' => $sessionTwo->id, 'student_id' => $student->id, 'status' => 'Sakit']);

        $this->assertDatabaseCount('lesson_attendances', 2);
        $this->assertDatabaseHas('lesson_attendances', ['schedule_session_id' => $sessionOne->id, 'status' => 'Hadir']);
        $this->assertDatabaseHas('lesson_attendances', ['schedule_session_id' => $sessionTwo->id, 'status' => 'Sakit']);
    }
}