<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\StudentLeaveRequest;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentLeaveRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_submit_leave_request_and_teacher_can_approve_it(): void
    {
        $studentUser = Student::create([
            'password' => 'password',
            'nisn' => '0010000001',
            'nik' => '3201010101010001',
            'qr_code_key' => 'qr-key-1',
            'nama_lengkap' => 'Siswa Uji',
            'email' => 'siswa.test@fathululum.sch.id',
            'unit' => 'mi',
            'kelas' => 6,
            'jenis_kelamin' => 'L',
            'status_aktif' => true,
        ]);

        $student = $studentUser;

        $this->actingAs($studentUser);

        $response = $this->post(route('dashboard.student.leave-requests.store'), [
            'jenis_izin' => 'sakit',
            'tanggal_mulai' => '2026-08-21',
            'tanggal_selesai' => '2026-08-22',
            'keterangan' => 'Demam ringan',
            'lampiran' => null,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('student_leave_requests', [
            'student_id' => $student->id,
            'status' => 'pending',
            'jenis_izin' => 'sakit',
        ]);

        $leaveRequest = StudentLeaveRequest::first();

        $teacherUser = Teacher::create([
            'password' => 'password',
            'nip_nuptk' => '19850001',
            'nama_lengkap' => 'Guru Uji',
            'email' => 'guru.test@fathululum.sch.id',
            'unit' => 'mi',
        ]);

        $this->actingAs($teacherUser);

        $approvalResponse = $this->post(route('dashboard.leave-requests.review', $leaveRequest), [
            'action' => 'approve',
            'catatan' => 'Dokter menyatakan izin diperlukan.',
        ]);

        $approvalResponse->assertRedirect();
        $this->assertDatabaseHas('student_leave_requests', [
            'id' => $leaveRequest->id,
            'status' => 'approved',
            'reviewed_by_type' => Teacher::class,
            'reviewed_by_id' => $teacherUser->id,
        ]);
    }

    public function test_teacher_can_record_attendance_without_student_qr_card(): void
    {
        $teacher = Teacher::create([
            'email' => 'guru.manual@fathululum.sch.id',
            'password' => 'password',
            'nip_nuptk' => '19850099',
            'nama_lengkap' => 'Guru Manual',
            'unit' => 'mi',
        ]);
        $student = Student::create([
            'email' => 'siswa.manual@fathululum.sch.id',
            'password' => 'password',
            'nisn' => '0090000001',
            'qr_code_key' => 'qr-manual-1',
            'nama_lengkap' => 'Siswa Manual',
            'unit' => 'mi',
            'kelas' => 6,
            'jenis_kelamin' => 'P',
            'status_aktif' => true,
        ]);

        $this->actingAs($teacher);

        $response = $this->postJson(route('dashboard.scan.manual'), ['student_id' => $student->id]);

        $response->assertOk()->assertJsonPath('student', 'Siswa Manual');
        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'metode' => 'QR_Scan',
            'recorded_by_type' => Teacher::class,
            'recorded_by_id' => $teacher->id,
        ]);

        $duplicate = $this->postJson(route('dashboard.scan.manual'), ['student_id' => $student->id]);
        $duplicate->assertStatus(409);
    }

    public function test_teacher_can_only_access_students_and_leave_requests_in_their_unit(): void
    {
        $teacher = Teacher::create([
            'email' => 'guru.mts@fathululum.sch.id',
            'password' => 'password',
            'nip_nuptk' => '19850100',
            'nama_lengkap' => 'Guru MTs',
            'unit' => 'mts',
        ]);
        $student = Student::create([
            'email' => 'siswa.mi@fathululum.sch.id',
            'password' => 'password',
            'nisn' => '0090000002',
            'qr_code_key' => 'qr-mi-2',
            'nama_lengkap' => 'Siswa MI',
            'unit' => 'mi',
            'kelas' => 6,
            'jenis_kelamin' => 'L',
            'status_aktif' => true,
        ]);
        $leaveRequest = StudentLeaveRequest::create([
            'student_id' => $student->id,
            'jenis_izin' => 'sakit',
            'tanggal_mulai' => '2026-08-21',
            'tanggal_selesai' => '2026-08-21',
            'keterangan' => 'Tidak enak badan',
            'status' => 'pending',
        ]);

        $this->actingAs($teacher);

        $this->getJson(route('dashboard.scan.students', ['q' => 'Siswa']))
            ->assertOk()
            ->assertJsonCount(0, 'students');
        $this->postJson(route('dashboard.scan.manual'), ['student_id' => $student->id])
            ->assertNotFound();
        $this->get(route('dashboard.leave-requests.review-index'))
            ->assertOk()
            ->assertDontSee('Siswa MI');
        $this->post(route('dashboard.leave-requests.review', $leaveRequest), [
            'action' => 'approve',
        ])->assertForbidden();
    }
}
