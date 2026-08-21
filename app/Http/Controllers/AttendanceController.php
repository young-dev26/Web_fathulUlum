<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function scanner(): View
    {
        return view('dashboard.scan-qr');
    }

    public function searchStudents(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q'));

        if (mb_strlen($query) < 2) {
            return response()->json(['students' => []]);
        }

        $students = Student::query()
            ->where('status_aktif', true)
            ->when($request->user()?->effectiveRole() === 'guru', fn ($builder) => $builder->where('unit', $request->user()->unit))
            ->where(fn ($builder) => $builder
                ->where('nama_lengkap', 'like', '%'.$query.'%')
                ->orWhere('nisn', 'like', '%'.$query.'%'))
            ->orderBy('nama_lengkap')
            ->limit(8)
            ->get(['id', 'nama_lengkap', 'nisn', 'unit', 'kelas']);

        return response()->json(['students' => $students]);
    }

    public function manualScan(Request $request): JsonResponse
    {
        $data = $request->validate(['student_id' => ['required', 'exists:students,id']]);
        $student = $this->studentAccessibleTo($request, $data['student_id']);

        $attendance = $this->recordAttendance($student, $request);

        if (! $attendance->wasRecentlyCreated) {
            return response()->json(['message' => 'Siswa sudah tercatat hadir hari ini.', 'student' => $student->nama_lengkap, 'duplicate' => true], 409);
        }

        return response()->json(['message' => 'Hadir: '.$student->nama_lengkap, 'student' => $student->nama_lengkap, 'jam_masuk' => $attendance->jam_masuk]);
    }

    public function scan(Request $request): JsonResponse
    {
        $data = $request->validate(['qr_code_key' => ['required', 'string', 'max:64']]);
        $student = Student::where('qr_code_key', $data['qr_code_key'])->where('status_aktif', true)->first();

        if ($student && ! $this->canAccessStudent($request, $student)) {
            $student = null;
        }

        if (! $student) {
            return response()->json(['message' => 'QR Code siswa tidak dikenali.'], 404);
        }

        $attendance = $this->recordAttendance($student, $request);

        if (! $attendance->wasRecentlyCreated) {
            return response()->json(['message' => 'Siswa sudah tercatat hadir hari ini.', 'student' => $student->nama_lengkap, 'duplicate' => true], 409);
        }

        return response()->json(['message' => 'Hadir: '.$student->nama_lengkap, 'student' => $student->nama_lengkap, 'jam_masuk' => $attendance->jam_masuk]);
    }

    private function recordAttendance(Student $student, Request $request): Attendance
    {
        $attendance = Attendance::where('student_id', $student->id)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        if ($attendance) {
            return $attendance;
        }

        try {
            return Attendance::create([
                'student_id' => $student->id,
                'tanggal' => now()->toDateString(),
                'jam_masuk' => now()->format('H:i:s'),
                'status' => 'Hadir',
                'metode' => 'QR_Scan',
                'recorded_by_user_id' => $request->user() instanceof User ? $request->user()->id : null,
                'recorded_by_type' => get_class($request->user()),
                'recorded_by_id' => $request->user()->getKey(),
            ]);
        } catch (QueryException $exception) {
            if (! str_contains(strtolower($exception->getMessage()), 'unique')) {
                throw $exception;
            }

            return Attendance::where('student_id', $student->id)
                ->whereDate('tanggal', now()->toDateString())
                ->firstOrFail();
        }
    }

    public function index(Request $request): View
    {
        $date = $request->date('tanggal')?->toDateString() ?? now()->toDateString();
        $attendances = Attendance::with('student')
            ->whereDate('tanggal', $date)
            ->when($request->user()?->effectiveRole() === 'guru', fn ($query) => $query->whereHas('student', fn ($student) => $student->where('unit', $request->user()->unit)))
            ->when($request->filled('q'), fn ($query) => $query->whereHas('student', fn ($student) => $student->where('nama_lengkap', 'like', '%'.$request->string('q').'%')->orWhere('nisn', 'like', '%'.$request->string('q').'%')))
            ->when($request->filled('unit'), fn ($query) => $query->whereHas('student', fn ($student) => $student->where('unit', $request->string('unit'))))
            ->when($request->filled('kelas'), fn ($query) => $query->whereHas('student', fn ($student) => $student->where('kelas', $request->integer('kelas'))))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('metode'), fn ($query) => $query->where('metode', $request->string('metode')))
            ->latest('jam_masuk')->paginate(25)->withQueryString();
        return view('dashboard.attendances.index', compact('attendances', 'date'));
    }

    public function storeManual(Request $request): RedirectResponse
    {
        $data = $request->validate(['student_id' => ['required', 'exists:students,id'], 'tanggal' => ['required', 'date'], 'status' => ['required', 'in:Hadir,Izin,Sakit,Alpha'], 'keterangan' => ['nullable', 'string']]);
            $recorder = $request->user();
            $student = $this->studentAccessibleTo($request, $data['student_id']);
            Attendance::updateOrCreate(
                ['student_id' => $student->id, 'tanggal' => $data['tanggal']],
                [
                    'status' => $data['status'],
                    'metode' => 'Manual',
                    'keterangan' => $data['keterangan'] ?? null,
                    'jam_masuk' => $data['status'] === 'Hadir' ? now()->format('H:i:s') : null,
                    'recorded_by_user_id' => $recorder instanceof User ? $recorder->id : null,
                    'recorded_by_type' => get_class($recorder),
                    'recorded_by_id' => $recorder->getKey(),
                ],
            );
        return back()->with('status', 'Absensi berhasil disimpan.');
    }

    private function studentAccessibleTo(Request $request, int|string $studentId): Student
    {
        return Student::query()
            ->whereKey($studentId)
            ->where('status_aktif', true)
            ->tap(fn ($query) => $query->when($request->user()?->effectiveRole() === 'guru', fn ($builder) => $builder->where('unit', $request->user()->unit)))
            ->firstOrFail();
    }

    private function canAccessStudent(Request $request, Student $student): bool
    {
        return $request->user()?->effectiveRole() !== 'guru' || $student->unit === $request->user()->unit;
    }
}
