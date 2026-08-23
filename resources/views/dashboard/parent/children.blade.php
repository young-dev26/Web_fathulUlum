@extends('layouts.dashboard')

@section('content')
    <section class="p-5 lg:p-10">
        <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Portal orang tua</p>
        <h1 class="mt-2 font-display text-4xl text-emerald-950">Data anak</h1>
        <p class="mt-2 text-sm text-slate-500">Pantau absensi dan pembayaran anak yang terhubung dengan akun Anda.</p>
        <div class="mt-8 grid gap-5 xl:grid-cols-2">
            @forelse ($children as $child)
                <article class="dashboard-card rounded-3xl bg-white p-6">
                    <div class="flex items-start justify-between gap-4"><div><h2 class="text-xl font-bold text-emerald-950">{{ $child->nama_lengkap }}</h2><p class="mt-1 text-sm text-slate-500">{{ strtoupper($child->unit) }} · Kelas {{ $child->kelas }} · NISN {{ $child->nisn }}</p></div><span class="grid h-11 w-11 place-items-center rounded-2xl bg-emerald-100 text-emerald-800"><i class="fa-solid fa-child"></i></span></div>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2"><div class="rounded-2xl bg-emerald-50 p-4"><p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Absensi terakhir</p>@forelse($child->attendances as $attendance)<p class="mt-3 text-sm font-semibold text-emerald-950">{{ $attendance->tanggal->format('d/m/Y') }} · {{ $attendance->status }}</p>@empty<p class="mt-3 text-sm text-slate-500">Belum ada data absensi.</p>@endforelse</div><div class="rounded-2xl bg-amber-50 p-4"><p class="text-xs font-bold uppercase tracking-wider text-amber-700">Pembayaran terakhir</p>@forelse($child->payments as $payment)<p class="mt-3 text-sm font-semibold text-emerald-950">{{ $payment->bulan }}/{{ $payment->tahun }} · {{ ucfirst(str_replace('_', ' ', $payment->status_bayar)) }}</p>@empty<p class="mt-3 text-sm text-slate-500">Belum ada data pembayaran.</p>@endforelse</div></div>
                </article>
            @empty
                <div class="dashboard-card rounded-3xl bg-white p-8 text-center text-sm text-slate-500 xl:col-span-2">Belum ada siswa yang terhubung dengan akun orang tua ini.</div>
            @endforelse
        </div>
    </section>
@endsection