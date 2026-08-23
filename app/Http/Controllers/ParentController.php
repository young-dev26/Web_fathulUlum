<?php

namespace App\Http\Controllers;

use App\Models\ParentProfile;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ParentController extends Controller
{
    public function index(): View
    {
        return view('dashboard.parents.index', ['parents' => ParentProfile::with('children')->orderBy('nama_lengkap')->get(), 'students' => Student::where('status_aktif', true)->orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'nisn', 'unit'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('parents', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'hubungan' => ['nullable', 'string', 'max:50'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        $parent = ParentProfile::create([
            'nama_lengkap' => $data['nama_lengkap'], 'email' => $data['email'], 'password' => Hash::make($data['password']),
            'hubungan' => $data['hubungan'] ?? null, 'telepon' => $data['telepon'] ?? null,
        ]);
        $parent->children()->sync($data['student_ids'] ?? []);

        return back()->with('status', 'Akun orang tua berhasil dibuat.');
    }

    public function update(Request $request, ParentProfile $parent): RedirectResponse
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('parents', 'email')->ignore($parent)],
            'password' => ['nullable', 'string', 'min:8'], 'hubungan' => ['nullable', 'string', 'max:50'], 'telepon' => ['nullable', 'string', 'max:30'],
            'student_ids' => ['nullable', 'array'], 'student_ids.*' => ['exists:students,id'],
        ]);
        $parent->update(collect($data)->except(['password', 'student_ids'])->all());
        if (! empty($data['password'])) $parent->update(['password' => Hash::make($data['password'])]);
        $parent->children()->sync($data['student_ids'] ?? []);

        return back()->with('status', 'Data orang tua berhasil diperbarui.');
    }
}