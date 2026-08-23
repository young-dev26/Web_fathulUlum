@extends('layouts.dashboard')

@section('content')
    @php
        $displayName = $user->nama_lengkap ?? $user->name ?? 'Pengguna';
        $role = $user->effectiveRole();
    @endphp

    <section class="p-5 lg:p-10">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">{{ $portalLabel }} · {{ $roleLabel }}</p>
                <h1 class="mt-2 font-display text-4xl text-emerald-950">Ringkasan hari ini</h1>
                <p class="mt-2 text-sm text-slate-500">Selamat datang, {{ $displayName }}.</p>
            </div>
            <span class="rounded-full bg-white px-4 py-2 text-xs font-bold text-slate-500 shadow-sm">{{ now()->format('d/m/Y') }}</span>
        </div>

        <div class="mt-9 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @if ($role === 'admin')
                <a href="{{ route('dashboard.students.index', ['unit' => 'mi']) }}" class="dashboard-card rounded-xl bg-emerald-800 p-5 text-white">
                    <i class="fa-solid fa-child text-amber-300"></i><strong data-counter="{{ $stats['mi'] }}" class="mt-5 block font-display text-3xl">0</strong><span class="text-xs text-emerald-100/70">Siswa aktif MI</span>
                </a>
                <a href="{{ route('dashboard.students.index', ['unit' => 'mts']) }}" class="dashboard-card rounded-xl bg-white p-5">
                    <i class="fa-solid fa-graduation-cap text-emerald-700"></i><strong data-counter="{{ $stats['mts'] }}" class="mt-5 block font-display text-3xl text-emerald-950">0</strong><span class="text-xs text-slate-500">Siswa aktif MTs</span>
                </a>
                <a href="{{ route('dashboard.teachers.index') }}" class="dashboard-card rounded-xl bg-white p-5">
                    <i class="fa-solid fa-chalkboard-user text-emerald-700"></i><strong data-counter="{{ $stats['teachers'] }}" class="mt-5 block font-display text-3xl text-emerald-950">0</strong><span class="text-xs text-slate-500">Total guru</span>
                </a>
                <a href="{{ route('dashboard.attendances.index', ['tanggal' => now()->toDateString()]) }}" class="dashboard-card rounded-xl bg-amber-100 p-5">
                    <i class="fa-solid fa-qrcode text-amber-700"></i><strong data-counter="{{ $stats['present'] }}" class="mt-5 block font-display text-3xl text-emerald-950">0</strong><span class="text-xs text-emerald-900/60">Hadir hari ini</span>
                </a>
                <a href="{{ route('dashboard.payments.index') }}" class="dashboard-card rounded-xl bg-white p-5">
                    <i class="fa-solid fa-file-signature text-emerald-700"></i><strong data-counter="{{ $stats['paid'] }}" class="mt-5 block font-display text-3xl text-emerald-950">0</strong><span class="text-xs text-slate-500">Pembayaran lunas</span>
                </a>
                <a href="{{ route('dashboard.ppdb.index', ['status' => 'baru']) }}" class="dashboard-card rounded-xl bg-white p-5">
                    <i class="fa-solid fa-user-plus text-emerald-700"></i><strong data-counter="{{ $stats['ppdb_new'] }}" class="mt-5 block font-display text-3xl text-emerald-950">0</strong><span class="text-xs text-slate-500">PPDB baru</span>
                </a>
            @else
                <a href="{{ route('dashboard.attendances.index') }}" class="dashboard-card rounded-xl bg-emerald-800 p-5 text-white">
                    <i class="fa-solid fa-qrcode text-amber-300"></i><strong data-counter="{{ $stats['present'] }}" class="mt-5 block font-display text-3xl">0</strong><span class="text-xs text-emerald-100/70">Hadir hari ini</span>
                </a>
                <div class="dashboard-card rounded-xl bg-white p-5 sm:col-span-2 xl:col-span-4"><i class="fa-solid fa-circle-info text-emerald-700"></i><p class="mt-5 text-sm text-slate-500">Gunakan menu di samping untuk mengakses aktivitas portal Anda.</p></div>
            @endif
        </div>

        @if ($role === 'admin')
            <div class="mt-10 flex items-end justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Pusat kendali</p><h2 class="mt-2 font-display text-3xl text-emerald-950">Kelola seluruh aplikasi</h2></div><span class="hidden text-sm text-slate-500 sm:block">Semua area penting dalam satu tempat.</span></div>
            <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('dashboard.students.index') }}" class="dashboard-card rounded-2xl bg-white p-5"><i class="fa-solid fa-users text-xl text-emerald-700"></i><h3 class="mt-4 font-bold text-emerald-950">Data siswa</h3><p class="mt-1 text-sm text-slate-500">Tambah, edit, filter, dan cetak kartu siswa.</p><span class="mt-4 inline-flex text-xs font-bold text-emerald-700">Buka data <i class="fa-solid fa-arrow-right ml-2"></i></span></a>
                <a href="{{ route('dashboard.teachers.index') }}" class="dashboard-card rounded-2xl bg-white p-5"><i class="fa-solid fa-chalkboard-user text-xl text-emerald-700"></i><h3 class="mt-4 font-bold text-emerald-950">Data guru</h3><p class="mt-1 text-sm text-slate-500">Kelola akun dan unit kerja guru.</p><span class="mt-4 inline-flex text-xs font-bold text-emerald-700">Buka data <i class="fa-solid fa-arrow-right ml-2"></i></span></a>
                <a href="{{ route('dashboard.ppdb.index') }}" class="dashboard-card rounded-2xl bg-white p-5"><i class="fa-solid fa-file-signature text-xl text-emerald-700"></i><h3 class="mt-4 font-bold text-emerald-950">Pendaftar PPDB</h3><p class="mt-1 text-sm text-slate-500">Verifikasi dan ubah status pendaftar.</p><span class="mt-4 inline-flex text-xs font-bold text-emerald-700">Buka PPDB <i class="fa-solid fa-arrow-right ml-2"></i></span></a>
                <a href="{{ route('dashboard.site-settings.edit') }}" class="dashboard-card rounded-2xl bg-white p-5"><i class="fa-solid fa-sliders text-xl text-emerald-700"></i><h3 class="mt-4 font-bold text-emerald-950">Konten website</h3><p class="mt-1 text-sm text-slate-500">Atur hero, berita, kontak, dan PPDB.</p><span class="mt-4 inline-flex text-xs font-bold text-emerald-700">Atur konten <i class="fa-solid fa-arrow-right ml-2"></i></span></a>
                <a href="{{ route('dashboard.parents.index') }}" class="dashboard-card rounded-2xl bg-white p-5"><i class="fa-solid fa-user-group text-xl text-emerald-700"></i><h3 class="mt-4 font-bold text-emerald-950">Akun orang tua</h3><p class="mt-1 text-sm text-slate-500">Buat akun dan hubungkan anak.</p><span class="mt-4 inline-flex text-xs font-bold text-emerald-700">Kelola akun <i class="fa-solid fa-arrow-right ml-2"></i></span></a>
            </div>
        @elseif ($role === 'guru')
            <div class="mt-10"><p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Akses cepat</p><h2 class="mt-2 font-display text-3xl text-emerald-950">Aktivitas portal</h2></div>
            <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <a href="{{ route('dashboard.scan') }}" class="dashboard-card rounded-2xl bg-emerald-800 p-5 text-white"><i class="fa-solid fa-qrcode text-amber-300"></i><h3 class="mt-4 font-bold">Scan absensi</h3><p class="mt-1 text-sm text-emerald-100/70">Catat kehadiran siswa unit Anda.</p></a>
                <a href="{{ route('dashboard.attendances.index') }}" class="dashboard-card rounded-2xl bg-white p-5"><i class="fa-solid fa-calendar-check text-emerald-700"></i><h3 class="mt-4 font-bold text-emerald-950">Rekap absensi</h3><p class="mt-1 text-sm text-slate-500">Lihat laporan kehadiran siswa.</p></a>
                <a href="{{ route('dashboard.leave-requests.review-index') }}" class="dashboard-card rounded-2xl bg-white p-5"><i class="fa-solid fa-file-circle-check text-emerald-700"></i><h3 class="mt-4 font-bold text-emerald-950">Persetujuan izin</h3><p class="mt-1 text-sm text-slate-500">Tinjau pengajuan izin siswa.</p></a>
            </div>
        @elseif ($role === 'staff_tu')
            <div class="mt-10"><p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Administrasi sekolah</p><h2 class="mt-2 font-display text-3xl text-emerald-950">Pekerjaan hari ini</h2></div>
            <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <a href="{{ route('dashboard.attendances.index') }}" class="dashboard-card rounded-2xl bg-emerald-800 p-5 text-white"><i class="fa-solid fa-calendar-check text-amber-300"></i><h3 class="mt-4 font-bold">Rekap absensi</h3><p class="mt-1 text-sm text-emerald-100/70">Pantau dan rapikan data kehadiran.</p></a>
                <a href="{{ route('dashboard.leave-requests.review-index') }}" class="dashboard-card rounded-2xl bg-white p-5"><i class="fa-solid fa-file-circle-check text-emerald-700"></i><h3 class="mt-4 font-bold text-emerald-950">Surat dan persetujuan</h3><p class="mt-1 text-sm text-slate-500">Tinjau pengajuan izin siswa.</p></a>
                <a href="{{ route('dashboard.profile.edit') }}" class="dashboard-card rounded-2xl bg-white p-5"><i class="fa-solid fa-user-gear text-emerald-700"></i><h3 class="mt-4 font-bold text-emerald-950">Profil akun</h3><p class="mt-1 text-sm text-slate-500">Perbarui identitas dan kata sandi.</p></a>
            </div>
        @elseif ($role === 'orang_tua')
            <div class="mt-10"><p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Pemantauan keluarga</p><h2 class="mt-2 font-display text-3xl text-emerald-950">Pantau perkembangan anak</h2></div>
            <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <a href="{{ route('dashboard.parent.children') }}" class="dashboard-card rounded-2xl bg-emerald-800 p-5 text-white"><i class="fa-solid fa-children text-amber-300"></i><h3 class="mt-4 font-bold">Data anak</h3><p class="mt-1 text-sm text-emerald-100/70">Lihat absensi dan pembayaran anak.</p></a>
            </div>
        @else
            <div class="mt-10"><p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Akses cepat</p><h2 class="mt-2 font-display text-3xl text-emerald-950">Aktivitas saya</h2></div>
            <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <a href="{{ route('dashboard.student.attendance') }}" class="dashboard-card rounded-2xl bg-white p-5"><i class="fa-solid fa-calendar-check text-emerald-700"></i><h3 class="mt-4 font-bold text-emerald-950">Absensi saya</h3><p class="mt-1 text-sm text-slate-500">Lihat riwayat kehadiran.</p></a>
                <a href="{{ route('dashboard.student.card') }}" class="dashboard-card rounded-2xl bg-white p-5"><i class="fa-solid fa-id-card text-emerald-700"></i><h3 class="mt-4 font-bold text-emerald-950">Kartu pelajar</h3><p class="mt-1 text-sm text-slate-500">Buka dan cetak kartu pelajar.</p></a>
                <a href="{{ route('dashboard.student.leave-requests.index') }}" class="dashboard-card rounded-2xl bg-white p-5"><i class="fa-solid fa-file-medical text-emerald-700"></i><h3 class="mt-4 font-bold text-emerald-950">Izin siswa</h3><p class="mt-1 text-sm text-slate-500">Ajukan dan pantau izin.</p></a>
            </div>
        @endif
    </section>
@endsection
