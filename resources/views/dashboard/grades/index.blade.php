@extends('layouts.dashboard')

@section('content')
    <section class="p-5 lg:p-10">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div><p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Akademik</p><h1 class="mt-2 font-display text-4xl text-emerald-950">Raport dan nilai</h1><p class="mt-2 text-sm text-slate-500">Nilai tersusun berdasarkan tahun ajaran, semester, dan mata pelajaran.</p></div>
            @if ($role === 'admin' || $role === 'staff_tu' || $role === 'guru')<span class="rounded-full bg-emerald-100 px-4 py-2 text-xs font-bold text-emerald-800">Mode pengelolaan</span>@endif
        </div>

        @if ($role !== 'siswa' && $role !== 'orang_tua')
            <form method="get" class="dashboard-card mt-8 flex flex-wrap gap-3 rounded-2xl bg-white p-4"><input name="q" value="{{ request('q') }}" placeholder="Cari nama, NIS, atau NISN" class="min-w-[240px] flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm"><button class="rounded-xl bg-emerald-800 px-5 py-3 text-sm font-bold text-white"><i class="fa-solid fa-magnifying-glass mr-2"></i>Cari siswa</button></form>
        @endif

        <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(220px,300px)_1fr]">
            @if ($role !== 'siswa')
                <aside class="dashboard-card rounded-2xl bg-white p-4"><p class="px-2 text-xs font-bold uppercase tracking-wider text-slate-400">Daftar siswa</p><div class="mt-3 max-h-[480px] space-y-1 overflow-y-auto">@forelse($students as $student)<a href="{{ route('dashboard.grades.index', ['student_id' => $student->id]) }}" class="block rounded-xl px-3 py-3 text-sm {{ $selectedStudent?->id === $student->id ? 'bg-emerald-800 font-bold text-white' : 'hover:bg-emerald-50' }}"><span class="block">{{ $student->nama_lengkap }}</span><span class="text-xs opacity-70">{{ $student->nis ?? $student->nisn }} · {{ strtoupper($student->unit) }}</span></a>@empty<p class="p-3 text-sm text-slate-500">Belum ada siswa.</p>@endforelse</div></aside>
            @endif
            <div>
                @if ($selectedStudent)
                    <div class="rounded-2xl bg-emerald-800 p-6 text-white"><p class="text-xs font-bold uppercase tracking-wider text-amber-300">{{ strtoupper($selectedStudent->unit) }} · Kelas {{ $selectedStudent->kelas }}</p><h2 class="mt-2 font-display text-3xl">{{ $selectedStudent->nama_lengkap }}</h2><p class="mt-1 text-sm text-emerald-100/70">NIS {{ $selectedStudent->nis ?? '-' }} · NISN {{ $selectedStudent->nisn }}</p></div>
                    @if (in_array($role, ['admin', 'staff_tu', 'guru'], true))
                        <form action="{{ route('dashboard.grades.store') }}" method="post" class="dashboard-card mt-5 grid gap-3 rounded-2xl bg-white p-5 sm:grid-cols-2 lg:grid-cols-3">@csrf<input type="hidden" name="student_id" value="{{ $selectedStudent->id }}"><select name="semester" required class="rounded-xl border border-slate-200 px-3 py-3 text-sm"><option value="1">Semester 1</option><option value="2">Semester 2</option></select><input name="tahun_ajaran" value="{{ old('tahun_ajaran', '2026/2027') }}" pattern="[0-9]{4}/[0-9]{4}" required placeholder="Tahun ajaran" class="rounded-xl border border-slate-200 px-3 py-3 text-sm"><input name="mata_pelajaran" required placeholder="Mata pelajaran" class="rounded-xl border border-slate-200 px-3 py-3 text-sm"><input name="nilai_pengetahuan" type="number" min="0" max="100" step="0.01" placeholder="Nilai pengetahuan" class="rounded-xl border border-slate-200 px-3 py-3 text-sm"><input name="nilai_keterampilan" type="number" min="0" max="100" step="0.01" placeholder="Nilai keterampilan" class="rounded-xl border border-slate-200 px-3 py-3 text-sm"><button class="rounded-xl bg-amber-500 px-4 py-3 text-sm font-bold text-white hover:bg-amber-600"><i class="fa-solid fa-plus mr-2"></i>Simpan nilai</button></form>
                    @endif
                    <div class="mt-5 overflow-x-auto rounded-2xl bg-white shadow-sm"><table class="w-full min-w-[620px] text-left text-sm"><thead class="border-b border-slate-100 text-xs uppercase tracking-wider text-slate-400"><tr><th class="p-5">Semester</th><th>Tahun ajaran</th><th>Mata pelajaran</th><th>Pengetahuan</th><th>Keterampilan</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($grades as $grade)<tr><td class="p-5">{{ $grade->semester }}</td><td>{{ $grade->tahun_ajaran }}</td><td class="font-semibold">{{ $grade->mata_pelajaran }}</td><td>{{ $grade->nilai_pengetahuan ?? '-' }}</td><td>{{ $grade->nilai_keterampilan ?? '-' }}</td></tr>@empty<tr><td colspan="5" class="p-10 text-center text-slate-400">Belum ada nilai raport untuk siswa ini.</td></tr>@endforelse</tbody></table></div>
                @else
                    <div class="rounded-2xl bg-white p-10 text-center text-sm text-slate-500">Pilih siswa untuk melihat raport.</div>
                @endif
            </div>
        </div>
    </section>
@endsection