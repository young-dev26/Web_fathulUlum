<?php

namespace App\Http\Controllers;

use App\Models\PpdbApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PpdbApplicationController extends Controller
{
    public function create(): View
    {
        return view('ppdb.register');
    }

    public function store(Request $request): View
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nisn' => ['nullable', 'string', 'max:20'],
            'nama_wali' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'telepon' => ['required', 'string', 'max:30'],
            'unit' => ['required', 'in:mi,mts'],
            'kelas_tujuan' => ['required', 'integer', 'between:1,9'],
            'alamat' => ['required', 'string', 'max:1000'],
        ]);

        $application = PpdbApplication::create($data + [
            'nomor_pendaftaran' => 'PPDB-'.now()->format('Ymd').'-'.strtoupper(Str::random(5)),
        ]);

        return view('ppdb.success', compact('application'));
    }

    public function index(Request $request): View
    {
        $applications = PpdbApplication::query()
            ->when($request->filled('q'), fn ($query) => $query->where(fn ($query) => $query->where('nama_lengkap', 'like', '%'.$request->string('q').'%')->orWhere('nomor_pendaftaran', 'like', '%'.$request->string('q').'%')->orWhere('telepon', 'like', '%'.$request->string('q').'%')))
            ->when($request->filled('unit'), fn ($query) => $query->where('unit', $request->string('unit')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()->paginate(20)->withQueryString();

        return view('dashboard.ppdb.index', compact('applications'));
    }

    public function update(Request $request, PpdbApplication $application): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:baru,diverifikasi,diterima,ditolak'], 'catatan' => ['nullable', 'string', 'max:1000']]);
        $application->update($data);
        return back()->with('status', 'Status pendaftar PPDB berhasil diperbarui.');
    }
}