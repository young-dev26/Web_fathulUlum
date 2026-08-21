<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard | Fathul Ulum' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <style>
        :root { --dashboard-ink: #12372e; --dashboard-gold: #f59e0b; }
        body { font-family: 'DM Sans', sans-serif; background-image: radial-gradient(circle at 85% 8%, rgba(245,158,11,.12), transparent 22%), radial-gradient(circle at 10% 90%, rgba(6,95,70,.08), transparent 25%); }
        .font-display { font-family: 'Fraunces', Georgia, serif; }
        .dashboard-shell { background: rgba(244,248,245,.72); }
        .dashboard-sidebar { background: linear-gradient(155deg, #12372e 0%, #075b47 62%, #0d7660 100%); box-shadow: 14px 0 40px rgba(6,95,70,.08); }
        .dashboard-sidebar::after { content: ''; position: absolute; right: -90px; bottom: 12%; width: 190px; height: 190px; border: 1px solid rgba(252,211,77,.22); border-radius: 999px; animation: dashboard-orbit 18s linear infinite; }
        .dashboard-nav-link { position: relative; transition: transform .25s ease, background .25s ease, color .25s ease; }
        .dashboard-nav-link:hover { transform: translateX(4px); background: rgba(255,255,255,.1); }
        .dashboard-nav-link.is-active { background: rgba(255,255,255,.14); color: #fff; box-shadow: inset 3px 0 0 #fbbf24; }
        .dashboard-nav-link.is-active i { color: #fcd34d; }
        .dashboard-content { animation: dashboard-enter .55s cubic-bezier(.2,.75,.25,1) both; }
        .dashboard-card { border: 1px solid rgba(15,118,110,.08); box-shadow: 0 14px 40px rgba(15,61,49,.06); transition: transform .3s ease, box-shadow .3s ease; }
        .dashboard-card:hover { transform: translateY(-4px); box-shadow: 0 20px 44px rgba(15,61,49,.1); }
        .dashboard-toast { animation: dashboard-enter .45s ease both; }
        .scan-line { animation: scan-line 2.4s ease-in-out infinite; }
        .scan-action { transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease; }
        .scan-action:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(6,95,70,.18); }
        .scan-action:disabled { cursor: not-allowed; opacity: .45; }
        main table tbody tr { transition: background-color .2s ease; }
        main table tbody tr:hover { background-color: rgba(236,253,245,.55); }
        main input, main select, main textarea { transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease; }
        main input:focus, main select:focus, main textarea:focus { border-color: rgba(5,150,105,.55); box-shadow: 0 0 0 4px rgba(16,185,129,.1); outline: none; }
        main button { transition: transform .2s ease, box-shadow .2s ease, background-color .2s ease; }
        main button:hover { box-shadow: 0 10px 20px rgba(6,95,70,.12); }
        @keyframes dashboard-enter { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes dashboard-orbit { to { transform: rotate(360deg); } }
        @keyframes scan-line { 0%,100% { transform: translateY(-70px); opacity: .4; } 50% { transform: translateY(70px); opacity: 1; } }
        .dashboard-menu-button { border: 2px solid #10211d; color: #075b47; transition: background-color .2s ease, transform .2s ease; }
        .dashboard-menu-button:hover { background: #ecfdf5; transform: translateY(-1px); }
        .dashboard-menu-button i { font-size: 1.35rem; }
        .dashboard-overlay { background: rgba(6, 47, 39, .42); }
        @media (min-width: 1024px) { .dashboard-sidebar { transition: transform .3s ease; } .dashboard-sidebar.is-collapsed { transform: translateX(-100%); } .dashboard-content.is-expanded { margin-left: 0; } }
        @media (max-width: 1023px) { .dashboard-sidebar { transition: max-height .35s ease, opacity .35s ease; max-height: 720px; overflow: hidden; } .dashboard-sidebar.is-collapsed { max-height: 0; opacity: 0; } }
        @media (prefers-reduced-motion: reduce) { .dashboard-sidebar::after, .dashboard-content, .dashboard-toast, .scan-line { animation: none; } .dashboard-sidebar, .dashboard-nav-link, .dashboard-card { transition: none; } }
    </style>
</head>
<body class="bg-[#f4f8f5] text-slate-800">
    <div class="dashboard-shell min-h-screen lg:flex">
        <div data-dashboard-overlay class="dashboard-overlay fixed inset-0 z-10 hidden lg:hidden"></div>
        <aside data-dashboard-sidebar class="dashboard-sidebar relative z-20 w-full text-white lg:fixed lg:inset-y-0 lg:flex lg:w-72 lg:flex-col">
            <div class="flex items-center justify-between border-b border-white/10 px-6 py-5">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-amber-400 text-emerald-950">
                        <i class="fa-solid fa-mosque"></i>
                    </span>
                    <span>
                        <strong class="block text-sm">Fathul Ulum</strong>
                        <small class="text-xs text-emerald-100/60">Portal Administrasi</small>
                    </span>
                </a>
                <button type="button" data-dashboard-toggle aria-expanded="true" class="text-amber-300" title="Tutup menu navigasi"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <nav class="flex gap-1 overflow-x-auto px-4 py-4 lg:block lg:flex-1 lg:space-y-1">
                <a href="{{ route('dashboard') }}" class="dashboard-nav-link block whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-bold">
                    <i class="fa-solid fa-chart-pie mr-3 w-4 text-amber-300"></i>Ringkasan
                </a>

                @if (auth()->user()->effectiveRole() === 'siswa')
                    <a href="{{ route('dashboard.student.attendance') }}" class="dashboard-nav-link block whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-bold">
                        <i class="fa-solid fa-calendar-check mr-3 w-4 text-amber-300"></i>Absensi Saya
                    </a>
                    <a href="{{ route('dashboard.student.card') }}" class="dashboard-nav-link block whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-bold">
                        <i class="fa-solid fa-id-card mr-3 w-4 text-amber-300"></i>Kartu Pelajar
                    </a>
                @endif

                @if (in_array(auth()->user()->effectiveRole(), ['admin', 'guru'], true))
                    <a href="{{ route('dashboard.scan') }}" class="dashboard-nav-link block whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-bold">
                        <i class="fa-solid fa-qrcode mr-3 w-4 text-amber-300"></i>Scan Absensi
                    </a>
                    <a href="{{ route('dashboard.attendances.index') }}" class="dashboard-nav-link block whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-bold">
                        <i class="fa-solid fa-calendar-check mr-3 w-4 text-amber-300"></i>Rekap Absensi
                    </a>
                    <a href="{{ route('dashboard.leave-requests.review-index') }}" class="dashboard-nav-link block whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-bold">
                        <i class="fa-solid fa-file-circle-check mr-3 w-4 text-amber-300"></i>Persetujuan Izin
                    </a>
                @endif

                @if (auth()->user()->effectiveRole() === 'siswa')
                    <a href="{{ route('dashboard.student.leave-requests.index') }}" class="dashboard-nav-link block whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-bold">
                        <i class="fa-solid fa-file-medical mr-3 w-4 text-amber-300"></i>Izin Siswa
                    </a>
                @endif

                @if (auth()->user()->effectiveRole() === 'admin')
                    <a href="{{ route('dashboard.students.index') }}" class="dashboard-nav-link block whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-bold">
                        <i class="fa-solid fa-users mr-3 w-4 text-amber-300"></i>Data Siswa
                    </a>
                    <a href="{{ route('dashboard.teachers.index') }}" class="dashboard-nav-link block whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-bold">
                        <i class="fa-solid fa-chalkboard-user mr-3 w-4 text-amber-300"></i>Data Guru
                    </a>
                    <a href="{{ route('dashboard.site-settings.edit') }}" class="dashboard-nav-link block whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-bold">
                        <i class="fa-solid fa-sliders mr-3 w-4 text-amber-300"></i>Landing Page
                    </a>
                    <a href="{{ route('dashboard.ppdb.index') }}" class="dashboard-nav-link block whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-bold">
                        <i class="fa-solid fa-file-signature mr-3 w-4 text-amber-300"></i>Pendaftar PPDB
                    </a>
                @endif
            </nav>

            <div class="dashboard-account border-t border-white/10 p-5">
                <p class="truncate text-sm font-bold">{{ auth()->user()->nama_lengkap ?? auth()->user()->name ?? 'Pengguna' }}</p>
                <p class="mt-1 text-xs uppercase text-emerald-100/50">{{ auth()->user()->effectiveRole() ?? 'user' }}</p>
                <form class="mt-4" action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="text-xs font-bold text-amber-300 hover:text-white">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>Keluar
                    </button>
                </form>
            </div>
        </aside>

        <main data-dashboard-content class="dashboard-content w-full lg:ml-72">
            <header class="sticky top-0 z-10 flex items-center justify-between border-b border-emerald-950/5 bg-white/75 px-5 py-4 backdrop-blur-xl lg:px-10">
                <div class="flex items-center gap-3 sm:gap-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 lg:hidden">
                        <span class="grid h-12 w-12 place-items-center rounded-2xl bg-emerald-800 text-amber-300 shadow-sm"><i class="fa-solid fa-mosque text-xl"></i></span>
                        <span class="block"><strong class="block text-base leading-tight text-emerald-950 sm:text-lg">Yayasan PP. Fathul Ulum</strong><small class="mt-1 block text-[10px] uppercase tracking-[.22em] text-teal-700/70 sm:text-xs">Poteran · Talango</small></span>
                    </a>
                    <div class="hidden lg:block">
                        <p class="text-[10px] font-bold uppercase tracking-[.2em] text-emerald-700/60">Portal Fathul Ulum</p>
                        <p class="mt-1 text-sm font-semibold text-emerald-950">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" data-dashboard-open aria-expanded="false" class="dashboard-menu-button grid h-12 w-12 place-items-center rounded-xl bg-white lg:order-last" title="Buka menu navigasi" aria-label="Buka menu navigasi"><i class="fa-solid fa-bars"></i></button>
                    <span class="hidden text-right lg:block"><strong class="block text-sm text-emerald-950">{{ auth()->user()->nama_lengkap ?? auth()->user()->name ?? 'Pengguna' }}</strong><small class="text-[10px] uppercase tracking-[.15em] text-slate-400">{{ auth()->user()->effectiveRole() ?? 'user' }}</small></span>
                    <span class="hidden h-10 w-10 place-items-center rounded-full bg-amber-300 font-display text-lg text-emerald-950 lg:grid">{{ strtoupper(substr(auth()->user()->nama_lengkap ?? auth()->user()->name ?? 'P', 0, 1)) }}</span>
                    <form class="hidden lg:block" action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="grid h-10 w-10 place-items-center rounded-lg border border-emerald-900/10 text-emerald-800" title="Keluar" aria-label="Keluar dari portal">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </header>
            @if (session('status'))
                <div class="dashboard-toast mx-5 mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 lg:mx-10">
                    {{ session('status') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    <script>
        const dashboardToggle = document.querySelector('[data-dashboard-toggle]');
        const dashboardOpen = document.querySelector('[data-dashboard-open]');
        const dashboardSidebar = document.querySelector('[data-dashboard-sidebar]');
        const dashboardOverlay = document.querySelector('[data-dashboard-overlay]');
        const dashboardContent = document.querySelector('[data-dashboard-content]');
        const setDashboardMenu = (open) => {
            dashboardSidebar.classList.toggle('is-collapsed', !open);
            dashboardOverlay?.classList.toggle('hidden', !open);
            dashboardOpen?.setAttribute('aria-expanded', String(open));
            dashboardOpen?.setAttribute('title', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
            dashboardToggle?.setAttribute('aria-expanded', String(open));
            dashboardToggle?.setAttribute('title', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
            dashboardToggle.innerHTML = `<i class="fa-solid fa-${open ? 'xmark' : 'bars'}"></i>`;
            dashboardContent?.classList.toggle('is-expanded', !open);
        };
        dashboardOpen?.addEventListener('click', () => setDashboardMenu(true));
        dashboardToggle?.addEventListener('click', () => setDashboardMenu(false));
        dashboardOverlay?.addEventListener('click', () => setDashboardMenu(false));
        document.querySelectorAll('.dashboard-nav-link').forEach((link) => link.addEventListener('click', () => setDashboardMenu(false)));
        setDashboardMenu(false);

        const currentPath = window.location.pathname;
        document.querySelectorAll('.dashboard-nav-link').forEach((link) => {
            if (new URL(link.href).pathname === currentPath) link.classList.add('is-active');
        });
    </script>
</body>
</html>
