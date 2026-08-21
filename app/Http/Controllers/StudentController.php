<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function myAttendance(Request $request): View
    {
        $student = auth()->user() instanceof Student ? auth()->user() : null;
        abort_unless($student, 404);
        $attendances = $student->attendances()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('tanggal_mulai'), fn ($query) => $query->whereDate('tanggal', '>=', $request->date('tanggal_mulai')))
            ->when($request->filled('tanggal_selesai'), fn ($query) => $query->whereDate('tanggal', '<=', $request->date('tanggal_selesai')))
            ->latest('tanggal')->paginate(20)->withQueryString();
        return view('dashboard.student', compact('student', 'attendances'));
    }

    public function index(Request $request): View
    {
        $students = Student::query()
            ->when($request->filled('q'), fn ($query) => $query->where(fn ($query) => $query->where('nama_lengkap', 'like', '%'.$request->string('q').'%')->orWhere('nisn', 'like', '%'.$request->string('q').'%')))
            ->when($request->unit, fn ($query, $unit) => $query->where('unit', $unit))
            ->when($request->kelas, fn ($query, $kelas) => $query->where('kelas', $kelas))
            ->when($request->filled('status'), fn ($query) => $query->where('status_aktif', $request->string('status') === 'aktif'))
            ->orderBy('nama_lengkap')->paginate(20)->withQueryString();
        return view('dashboard.students.index', compact('students'));
    }

    public function create(): View { return view('dashboard.students.form', ['student' => new Student]); }

    public function edit(Student $student): View { return view('dashboard.students.form', compact('student')); }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['qr_code_key'] = Str::random(48);
        Student::create($data);
        return redirect()->route('dashboard.students.index')->with('status', 'Data siswa berhasil ditambahkan.');
    }

    public function card(Student $student): View { return view('dashboard.students.card', compact('student')); }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $data = $this->validated($request, $student);
        $student->update($data);
        return redirect()->route('dashboard.students.index')->with('status', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();
        return back()->with('status', 'Data siswa berhasil dihapus.');
    }

    private function validated(Request $request, ?Student $student = null): array
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($student)],
            'password' => [$student ? 'nullable' : 'required', 'string', 'min:8'],
            'nisn' => ['required', 'string', 'max:20', Rule::unique('students', 'nisn')->ignore($student)],
            'nik' => ['nullable', 'string', 'max:20'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'in:mi,mts'],
            'kelas' => ['required', 'integer', 'between:1,9'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'status_aktif' => ['boolean'],
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } elseif ($student) {
            unset($data['password']);
        }

        return $data;
    }
}
