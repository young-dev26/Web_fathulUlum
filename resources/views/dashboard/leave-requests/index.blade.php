@extends('layouts.dashboard')

@section('content')
<section class="p-5 lg:p-10">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Approval</p>
            <h1 class="mt-2 font-display text-4xl text-emerald-950">Persetujuan izin siswa</h1>
        </div>
    </div>

    <div class="mt-8 space-y-4">
        <form class="dashboard-card mt-8 flex flex-wrap items-center gap-2 rounded-2xl bg-white p-4" method="get">
            <div class="relative min-w-[230px] flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input name="q" value="{{ request('q') }}" placeholder="Cari nama siswa atau NISN..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-3 text-sm">
            </div>
            <select name="status" class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm"><option value="">Semua status</option>@foreach(['pending','approved','rejected'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
            <select name="jenis_izin" class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm"><option value="">Semua jenis</option>@foreach(['sakit','izin','alpha','lainnya'] as $jenis)<option value="{{ $jenis }}" @selected(request('jenis_izin') === $jenis)>{{ ucfirst($jenis) }}</option>@endforeach</select>
            <button class="rounded-xl bg-emerald-800 px-4 py-2.5 text-sm font-bold text-white"><i class="fa-solid fa-filter mr-2 text-amber-300"></i>Filter</button>
            <a href="{{ route('dashboard.leave-requests.review-index') }}" class="rounded-xl px-3 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-100">Reset</a>
        </form>
        @forelse ($requests as $request)
            <div class="dashboard-card rounded-3xl bg-white p-5 lg:p-7">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-emerald-950">{{ $request->student->nama_lengkap }}</h2>
                        <p class="text-sm text-slate-500">{{ $request->jenis_izin }} · {{ $request->tanggal_mulai->format('d M Y') }} s.d {{ $request->tanggal_selesai->format('d M Y') }}</p>
                    </div>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">{{ $request->status }}</span>
                </div>

                <p class="mt-4 text-sm text-slate-600">{{ $request->keterangan }}</p>

                <form method="post" action="{{ route('dashboard.leave-requests.review', $request) }}" class="mt-5 flex flex-wrap items-center gap-3">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <textarea name="catatan" rows="2" placeholder="Catatan persetujuan" class="min-w-[220px] flex-1 rounded-lg border border-slate-200 px-3 py-2.5"></textarea>
                    <button type="submit" class="rounded-lg bg-emerald-700 px-4 py-2.5 font-semibold text-white">Setujui</button>
                </form>

                <form method="post" action="{{ route('dashboard.leave-requests.review', $request) }}" class="mt-3">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <input type="text" name="catatan" placeholder="Alasan penolakan" class="w-full rounded-lg border border-slate-200 px-3 py-2.5">
                    <button type="submit" class="mt-3 rounded-lg bg-rose-600 px-4 py-2.5 font-semibold text-white">Tolak</button>
                </form>
            </div>
        @empty
            <div class="rounded-2xl bg-white p-6 text-sm text-slate-500 shadow-sm">
                Belum ada pengajuan izin yang perlu diproses.
            </div>
        @endforelse
    </div>
</section>
@endsection
