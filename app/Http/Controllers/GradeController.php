<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ParentProfile;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $role = $user->effectiveRole();
        $students = match ($role) {
            'siswa' => collect([$user]),
            'orang_tua' => $user->children()->orderBy('nama_lengkap')->get(),
            default => Student::query()->where('status_aktif', true)
                ->when($role === 'guru', fn ($query) => $query->where('unit', $user->unit))
                ->when($request->filled('q'), fn ($query) => $query->where(fn ($search) => $search->where('nama_lengkap', 'like', '%'.$request->string('q').'%')->orWhere('nis', 'like', '%'.$request->string('q').'%')->orWhere('nisn', 'like', '%'.$request->string('q').'%')))
                ->orderBy('nama_lengkap')->get(),
        };

        $selectedStudent = $students->firstWhere('id', $request->integer('student_id')) ?? $students->first();
        $grades = $selectedStudent?->grades()->when($request->filled('tahun_ajaran'), fn ($query) => $query->where('tahun_ajaran', $request->string('tahun_ajaran')))->orderBy('semester')->orderBy('mata_pelajaran')->get() ?? collect();

        return view('dashboard.grades.index', compact('students', 'selectedStudent', 'grades', 'role'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(in_array($request->user()->effectiveRole(), ['admin', 'staff_tu', 'guru'], true), 403);
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'semester' => ['required', 'integer', 'in:1,2'],
            'tahun_ajaran' => ['required', 'string', 'max:20', 'regex:/^\d{4}\/\d{4}$/'],
            'mata_pelajaran' => ['required', 'string', 'max:100'],
            'nilai_pengetahuan' => ['nullable', 'numeric', 'between:0,100'],
            'nilai_keterampilan' => ['nullable', 'numeric', 'between:0,100'],
        ]);
        $student = Student::findOrFail($data['student_id']);
        abort_unless($request->user()->effectiveRole() !== 'guru' || $student->unit === $request->user()->unit, 403);
        Grade::updateOrCreate(
            ['student_id' => $student->id, 'semester' => $data['semester'], 'tahun_ajaran' => $data['tahun_ajaran'], 'mata_pelajaran' => $data['mata_pelajaran']],
            ['nilai_pengetahuan' => $data['nilai_pengetahuan'] ?? null, 'nilai_keterampilan' => $data['nilai_keterampilan'] ?? null],
        );

        return redirect()->route('dashboard.grades.index', ['student_id' => $student->id])->with('status', 'Nilai raport berhasil disimpan.');
    }
}