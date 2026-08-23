<?php

namespace App\Http\Controllers;

use App\Models\LessonAttendance;
use App\Models\ParentProfile;
use App\Models\Schedule;
use App\Models\ScheduleSession;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LessonAttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        if ($user instanceof Student) {
            $records = $user->lessonAttendances()->with('session.schedule.assignment.subject')->latest()->paginate(25);
            return view('dashboard.lesson-attendance.student', compact('records'));
        }
        if ($user instanceof ParentProfile) {
            $childIds = $user->children()->pluck('students.id');
            $records = LessonAttendance::with(['student', 'session.schedule.assignment.subject', 'session.schedule.slot'])->whereIn('student_id', $childIds)->latest()->paginate(25);
            return view('dashboard.lesson-attendance.student', compact('records'));
        }

        $sessions = ScheduleSession::with(['schedule.assignment.subject', 'schedule.assignment.rombel', 'schedule.slot'])
            ->whereDate('session_date', $request->date('tanggal') ?? now())
            ->when($user instanceof Teacher, fn ($query) => $query->whereHas('schedule.assignment', fn ($assignment) => $assignment->where('teacher_id', $user->id)))
            ->latest()->get();
        return view('dashboard.lesson-attendance.index', compact('sessions'));
    }

    public function open(Request $request, Schedule $schedule): RedirectResponse
    {
        $this->authorizeSchedule($request, $schedule);
        $session = ScheduleSession::firstOrCreate(['schedule_id' => $schedule->id, 'session_date' => now()->toDateString()], ['opened_by' => $request->user() instanceof Teacher ? $request->user()->id : null]);
        return redirect()->route('dashboard.lesson-attendance.show', $session);
    }

    public function show(Request $request, ScheduleSession $session): View
    {
        $session->load(['schedule.assignment.subject', 'schedule.assignment.rombel.students', 'schedule.slot', 'attendances']);
        $this->authorizeSchedule($request, $session->schedule);
        return view('dashboard.lesson-attendance.show', compact('session'));
    }

    public function store(Request $request, ScheduleSession $session): RedirectResponse
    {
        $session->load('schedule.assignment.rombel.students');
        $this->authorizeSchedule($request, $session->schedule);
        abort_if($session->status === 'closed', 422, 'Sesi pelajaran sudah ditutup.');
        $data = $request->validate(['attendance' => ['required', 'array'], 'attendance.*' => ['required', 'in:Hadir,Izin,Sakit,Alpha']]);
        DB::transaction(function () use ($data, $session, $request) {
            foreach ($session->schedule->assignment->rombel->students as $student) {
                if (! isset($data['attendance'][$student->id])) continue;
                LessonAttendance::updateOrCreate(
                    ['schedule_session_id' => $session->id, 'student_id' => $student->id],
                    ['status' => $data['attendance'][$student->id], 'check_in_at' => $data['attendance'][$student->id] === 'Hadir' ? now()->format('H:i:s') : null, 'recorded_by_type' => get_class($request->user()), 'recorded_by_id' => $request->user()->getKey()],
                );
            }
            $session->update(['status' => 'closed']);
        });
        return redirect()->route('dashboard.lesson-attendance.index')->with('status', 'Absensi pelajaran berhasil disimpan.');
    }

    private function authorizeSchedule(Request $request, Schedule $schedule): void
    {
        $user = $request->user();
        abort_unless(in_array($user->effectiveRole(), ['admin', 'staff_tu', 'guru'], true), 403);
        abort_unless(! $user instanceof Teacher || $schedule->assignment()->where('teacher_id', $user->id)->exists(), 403);
    }
}