<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
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
            'present' => Attendance::whereDate('tanggal', now())->where('status', 'Hadir')->count(),
        ];

        return view('dashboard', compact('user', 'portalLabel', 'roleLabel', 'stats'));
    }
}
