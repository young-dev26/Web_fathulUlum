<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentLeaveRequestController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\PpdbApplicationController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/mi', [HomeController::class, 'mi'])->name('mi');
Route::get('/mts', [HomeController::class, 'mts'])->name('mts');
Route::get('/ppdb', [HomeController::class, 'ppdb'])->name('ppdb');
Route::get('/ppdb/daftar', [PpdbApplicationController::class, 'create'])->name('ppdb.register');
Route::post('/ppdb/daftar', [PpdbApplicationController::class, 'store'])->name('ppdb.register.store');
Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('dashboard')->name('dashboard.')->group(function () {
	Route::get('/site-settings', [SiteSettingController::class, 'edit'])->name('site-settings.edit');
	Route::put('/site-settings', [SiteSettingController::class, 'update'])->name('site-settings.update');
	Route::get('/ppdb', [PpdbApplicationController::class, 'index'])->name('ppdb.index');
	Route::put('/ppdb/{application}', [PpdbApplicationController::class, 'update'])->name('ppdb.update');
	Route::resource('/teachers', TeacherController::class)->except(['show']);
});

Route::middleware(['auth', 'role:admin,guru'])->prefix('dashboard')->name('dashboard.')->group(function () {
	Route::get('/scan-qr', [AttendanceController::class, 'scanner'])->name('scan');
	Route::get('/scan-qr/students', [AttendanceController::class, 'searchStudents'])->name('scan.students');
	Route::post('/scan-qr', [AttendanceController::class, 'scan'])->name('scan.store');
	Route::post('/scan-qr/manual', [AttendanceController::class, 'manualScan'])->name('scan.manual');
	Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
	Route::post('/attendances/manual', [AttendanceController::class, 'storeManual'])->name('attendances.manual');
	Route::get('/leave-requests-review', [StudentLeaveRequestController::class, 'indexForReview'])->name('leave-requests.review-index');
	Route::post('/leave-requests/{leaveRequest}/review', [StudentLeaveRequestController::class, 'review'])->name('leave-requests.review');
});

Route::middleware(['auth', 'role:admin'])->prefix('dashboard/students')->name('dashboard.students.')->group(function () {
	Route::get('/', [StudentController::class, 'index'])->name('index');
	Route::get('/create', [StudentController::class, 'create'])->name('create');
	Route::post('/', [StudentController::class, 'store'])->name('store');
	Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
	Route::put('/{student}', [StudentController::class, 'update'])->name('update');
	Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
	Route::get('/{student}/card', [StudentController::class, 'card'])->name('card');
});

Route::middleware(['auth', 'role:siswa'])->prefix('dashboard')->name('dashboard.student.')->group(function () {
	Route::get('/my-attendance', [StudentController::class, 'myAttendance'])->name('attendance');
	Route::get('/my-card', fn () => app(StudentController::class)->card(auth()->user()))->name('card');
	Route::get('/leave-requests', [StudentLeaveRequestController::class, 'indexForStudent'])->name('leave-requests.index');
	Route::post('/leave-requests', [StudentLeaveRequestController::class, 'store'])->name('leave-requests.store');
});
