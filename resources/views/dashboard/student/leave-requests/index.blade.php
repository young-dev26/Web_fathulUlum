@extends('layouts.dashboard')

@section('content')
<section class="p-5 lg:p-10">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Portal siswa</p>
            <h1 class="mt-2 font-display text-4xl text-emerald-950">Pengajuan izin</h1>
        </div>
    </div>

    <form class="dashboard-card mt-7 flex flex-wrap items-center gap-2 rounded-2xl bg-white p-4" method="get">
        <select name="status" class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm"><option value="">Semua status</option>@foreach(['pending','approved','rejected'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
        <select name="jenis_izin" class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm"><option value="">Semua jenis</option>@foreach(['sakit','izin','alpha','lainnya'] as $jenis)<option value="{{ $jenis }}" @selected(request('jenis_izin') === $jenis)>{{ ucfirst($jenis) }}</option>@endforeach</select>
        <button class="rounded-xl bg-emerald-800 px-4 py-2.5 text-sm font-bold text-white"><i class="fa-solid fa-filter mr-2 text-amber-300"></i>Filter riwayat</button>
        <a href="{{ route('dashboard.student.leave-requests.index') }}" class="rounded-xl px-3 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-100">Reset</a>
    </form>

    <div class="mt-8 grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
        <div class="dashboard-card rounded-3xl bg-white p-6 lg:p-8">
            <h2 class="text-xl font-bold text-emerald-950">Ajukan izin</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500">Isi data dengan lengkap agar pengajuan bisa diproses lebih cepat.</p>
            <form method="post" action="{{ route('dashboard.student.leave-requests.store') }}" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Jenis izin</label>
                    <select name="jenis_izin" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200">
                        <option value="sakit">Sakit</option>
                        <option value="izin">Izin</option>
                        <option value="alpha">Alpha</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Tanggal mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ now()->toDateString() }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Tanggal selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ now()->addDay()->toDateString() }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Keterangan</label>
                    <textarea name="keterangan" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200" required></textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Lampiran (opsional)</label>
                    <input type="text" name="lampiran" placeholder="Contoh: surat dokter" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200">
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-800 px-5 py-3 font-semibold text-white shadow-lg shadow-emerald-900/15 transition hover:-translate-y-0.5 hover:bg-emerald-700"><i class="fa-solid fa-paper-plane text-amber-300"></i>Kirim pengajuan</button>
            </form>
        </div>

        <div class="dashboard-card rounded-3xl bg-white p-6 lg:p-8">
            <h2 class="text-xl font-bold text-emerald-950">Riwayat</h2>
            <div class="mt-5 space-y-3">
                @forelse ($requests as $request)
                    <div class="dashboard-card rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <strong class="text-sm uppercase text-slate-700">{{ $request->jenis_izin }}</strong>
                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800">{{ $request->status }}</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-600">{{ $request->tanggal_mulai->format('d M Y') }} - {{ $request->tanggal_selesai->format('d M Y') }}</p>
                        <p class="mt-2 text-sm text-slate-500">{{ $request->keterangan }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada pengajuan izin.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection
