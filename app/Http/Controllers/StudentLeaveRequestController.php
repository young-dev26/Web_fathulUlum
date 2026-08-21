<?php

namespace App\Http\Controllers;

use App\Models\StudentLeaveRequest;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentLeaveRequestController extends Controller
{
    public function indexForStudent(Request $request): View
    {
        $student = auth()->user() instanceof Student ? auth()->user() : null;
        abort_unless($student, 404);

        $requests = $student->leaveRequests()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('jenis_izin'), fn ($query) => $query->where('jenis_izin', $request->string('jenis_izin')))
            ->latest()->paginate(20)->withQueryString();

        return view('dashboard.student.leave-requests.index', compact('student', 'requests'));
    }

    public function indexForReview(Request $request): View
    {
        $requests = StudentLeaveRequest::with(['student', 'reviewer'])
            ->when($request->filled('q'), fn ($query) => $query->whereHas('student', fn ($student) => $student->where('nama_lengkap', 'like', '%'.$request->string('q').'%')->orWhere('nisn', 'like', '%'.$request->string('q').'%')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('jenis_izin'), fn ($query) => $query->where('jenis_izin', $request->string('jenis_izin')))
            ->latest()->paginate(20)->withQueryString();

        return view('dashboard.leave-requests.index', compact('requests'));
    }

    public function store(Request $request): RedirectResponse
    {
        $student = auth()->user() instanceof Student ? auth()->user() : null;
        abort_unless($student, 404);

        $data = $request->validate([
            'jenis_izin' => ['required', 'in:sakit,izin,alpha,lainnya'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'keterangan' => ['required', 'string', 'max:1000'],
            'lampiran' => ['nullable', 'string', 'max:255'],
        ]);

        $student->leaveRequests()->create([
            'jenis_izin' => $data['jenis_izin'],
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'keterangan' => $data['keterangan'],
            'lampiran' => $data['lampiran'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard.student.leave-requests.index')->with('status', 'Pengajuan izin berhasil dikirim.');
    }

    public function review(Request $request, StudentLeaveRequest $leaveRequest): RedirectResponse
    {
        abort_unless(in_array(auth()->user()->effectiveRole(), ['admin', 'guru'], true), 403);

        $validated = $request->validate([
            'action' => ['required', 'in:approve,reject'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $leaveRequest->update([
            'status' => $validated['action'] === 'approve' ? 'approved' : 'rejected',
            'reviewed_by_type' => get_class(auth()->user()),
            'reviewed_by_id' => auth()->user()->getKey(),
            'catatan' => $validated['catatan'] ?? null,
        ]);

        $message = $validated['action'] === 'approve' ? 'Pengajuan izin telah disetujui.' : 'Pengajuan izin telah ditolak.';

        return redirect()->route('dashboard.leave-requests.review-index')->with('status', $message);
    }
}
