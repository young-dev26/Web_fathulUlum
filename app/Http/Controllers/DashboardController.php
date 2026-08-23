<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentProfile;
use App\Models\PpdbApplication;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $unit = strtolower((string) ($user->unit ?? 'yayasan'));
        $role = strtolower((string) ($user->effectiveRole() ?? 'admin'));

        $portalLabel = $unit === 'mi' ? 'MI' : ($unit === 'mts' ? 'MTs' : 'Yayasan');
        $roleLabel = match ($role) {
            'guru', 'teacher' => 'guru',
            'siswa', 'student' => 'siswa',
            'orang_tua', 'parent' => 'orang tua',
            default => 'admin',
        };

        $stats = [
            'mi' => Student::where('unit', 'mi')->where('status_aktif', true)->count(),
            'mts' => Student::where('unit', 'mts')->where('status_aktif', true)->count(),
            'teachers' => Teacher::count(),
            'paid' => Payment::where('status_bayar', 'lunas')->count(),
            'ppdb_new' => PpdbApplication::where('status', 'baru')->count(),
            'parents' => ParentProfile::count(),
            'present' => Attendance::whereDate('tanggal', now())->where('status', 'Hadir')->count(),
        ];

        $children = $user instanceof ParentProfile
            ? $user->children()->with(['attendances' => fn ($query) => $query->latest('tanggal')->limit(5), 'payments' => fn ($query) => $query->latest()->limit(5)])->get()
            : collect();

        return view('dashboard', compact('user', 'portalLabel', 'roleLabel', 'stats', 'children'));
    }

    public function parentChildren(): View
    {
        $user = auth()->user();
        abort_unless($user instanceof ParentProfile, 404);
        $children = $user->children()->with(['attendances' => fn ($query) => $query->latest('tanggal')->limit(10), 'payments' => fn ($query) => $query->latest()->limit(12)])->get();

        return view('dashboard.parent.children', compact('user', 'children'));
    }
}
