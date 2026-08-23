<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $payments = Payment::with('student')
            ->when($request->filled('q'), fn ($query) => $query->whereHas('student', fn ($student) => $student->where('nama_lengkap', 'like', '%'.$request->string('q').'%')->orWhere('nisn', 'like', '%'.$request->string('q').'%')))
            ->when($request->filled('unit'), fn ($query) => $query->whereHas('student', fn ($student) => $student->where('unit', $request->string('unit'))))
            ->when($request->filled('tahun'), fn ($query) => $query->where('tahun', $request->integer('tahun')))
            ->latest()->paginate(25)->withQueryString();

        return view('dashboard.payments.index', ['payments' => $payments, 'students' => Student::where('status_aktif', true)->orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'nisn', 'unit'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'between:2020,2100'],
            'nominal' => ['required', 'integer', 'min:0'],
            'status_bayar' => ['required', Rule::in(['lunas', 'belum_lunas'])],
        ]);

        Payment::updateOrCreate(
            ['student_id' => $data['student_id'], 'bulan' => $data['bulan'], 'tahun' => $data['tahun']],
            ['nominal' => $data['nominal'], 'status_bayar' => $data['status_bayar']],
        );

        return back()->with('status', 'Data pembayaran berhasil disimpan.');
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $data = $request->validate(['status_bayar' => ['required', Rule::in(['lunas', 'belum_lunas'])]]);
        $payment->update($data);

        return back()->with('status', 'Status pembayaran berhasil diperbarui.');
    }
}