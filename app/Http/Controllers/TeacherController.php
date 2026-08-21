<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(Request $request): View
    {
        $teachers = Teacher::query()
            ->when($request->filled('q'), fn ($query) => $query->where(fn ($query) => $query->where('nama_lengkap', 'like', '%'.$request->string('q').'%')->orWhere('email', 'like', '%'.$request->string('q').'%')->orWhere('nip_nuptk', 'like', '%'.$request->string('q').'%')))
            ->when($request->filled('unit'), fn ($query) => $query->where('unit', $request->string('unit')))
            ->orderBy('nama_lengkap')->paginate(20)->withQueryString();

        return view('dashboard.teachers.index', compact('teachers'));
    }

    public function create(): View { return view('dashboard.teachers.form', ['teacher' => new Teacher]); }

    public function store(Request $request): RedirectResponse
    {
        Teacher::create($this->validated($request));
        return redirect()->route('dashboard.teachers.index')->with('status', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher): View { return view('dashboard.teachers.form', compact('teacher')); }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $data = $this->validated($request, $teacher);
        if (blank($data['password'] ?? null)) unset($data['password']);
        $teacher->update($data);
        return redirect()->route('dashboard.teachers.index')->with('status', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $teacher->delete();
        return back()->with('status', 'Data guru berhasil dihapus.');
    }

    private function validated(Request $request, ?Teacher $teacher = null): array
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('teachers', 'email')->ignore($teacher)],
            'password' => [$teacher ? 'nullable' : 'required', 'string', 'min:8'],
            'nip_nuptk' => ['nullable', 'string', 'max:30', Rule::unique('teachers', 'nip_nuptk')->ignore($teacher)],
            'unit' => ['required', 'in:mi,mts,yayasan'],
            'mata_pelajaran' => ['nullable', 'string', 'max:255'],
        ]);

        if (! empty($data['password'])) $data['password'] = Hash::make($data['password']);
        return $data;
    }
}