<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Yayasan PP. Fathul Ulum' }}</title>
    <meta name="description" content="Portal resmi Yayasan PP. Fathul Ulum Poteran Talango">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root { --ink: #12372e; --emerald: #065f46; --gold: #d97706; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'DM Sans', sans-serif
        }

        .font-display {
            font-family: 'Fraunces', Georgia, serif
        }
        .reveal { opacity: 0; transform: translateY(22px); transition: opacity .7s ease, transform .7s cubic-bezier(.2,.75,.25,1); }
        .reveal.is-visible { opacity: 1; transform: translateY(0); }
        .float-slow { animation: float-slow 6s ease-in-out infinite; }
        .pulse-soft { animation: pulse-soft 2.8s ease-in-out infinite; }
        .map-float { animation: map-float 5s ease-in-out infinite; }
        .map-pulse { animation: map-pulse 2.2s ease-out infinite; }
        .map-route { stroke-dasharray: 12 10; animation: map-route 3s linear infinite; }
        .hero-photo { animation: hero-photo 18s ease-in-out infinite alternate; }
        .hero-sheen { animation: hero-sheen 8s ease-in-out infinite; }
        .hero-grid { background-image: linear-gradient(rgba(255,255,255,.09) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.09) 1px, transparent 1px); background-size: 56px 56px; mask-image: linear-gradient(to bottom, black, transparent 85%); }
        .hero-orbit { animation: hero-orbit 14s linear infinite; transform-origin: center; }
        .marquee-track { animation: marquee-track 28s linear infinite; width: max-content; }
        .text-shimmer { background: linear-gradient(110deg, #fcd34d 25%, #fff7c2 45%, #f59e0b 65%); background-size: 220% auto; -webkit-background-clip: text; background-clip: text; color: transparent; animation: text-shimmer 5s linear infinite; }
        .interactive-card { transition: transform .45s cubic-bezier(.2,.8,.2,1), box-shadow .45s ease, border-color .45s ease; }
        .interactive-card:hover { transform: translateY(-8px) rotate(.4deg); box-shadow: 0 24px 50px rgba(6,95,70,.14); }
        .icon-bounce { transition: transform .45s cubic-bezier(.2,.8,.2,1); }
        .interactive-card:hover .icon-bounce { transform: translateY(-4px) rotate(-8deg) scale(1.08); }
        .image-pan { transition: transform .8s cubic-bezier(.2,.8,.2,1), filter .8s ease; }
        .group:hover .image-pan { transform: scale(1.08); filter: saturate(1.15); }
        .section-glow { background-image: radial-gradient(circle at 15% 20%, rgba(245,158,11,.12), transparent 25%), radial-gradient(circle at 85% 80%, rgba(6,95,70,.1), transparent 28%); }
        .footer-orbit { animation: hero-orbit 22s linear infinite; }
        @keyframes float-slow { 0%,100% { transform: translateY(0) rotate(2deg); } 50% { transform: translateY(-12px) rotate(3deg); } }
        @keyframes pulse-soft { 0%,100% { box-shadow: 0 0 0 0 rgba(245,158,11,.28); } 50% { box-shadow: 0 0 0 10px rgba(245,158,11,0); } }
        @keyframes map-float { 0%,100% { transform: translateY(0) rotate(-1deg); } 50% { transform: translateY(-8px) rotate(1deg); } }
        @keyframes map-pulse { 0% { transform: scale(.7); opacity: .7; } 70%,100% { transform: scale(1.8); opacity: 0; } }
        @keyframes map-route { to { stroke-dashoffset: -44; } }
        @keyframes hero-photo { 0% { transform: scale(1.04) translate3d(-1%, -1%, 0); } 100% { transform: scale(1.14) translate3d(1%, 1%, 0); } }
        @keyframes hero-sheen { 0%,100% { opacity: .2; transform: translateX(-12%); } 50% { opacity: .5; transform: translateX(12%); } }
        @keyframes hero-orbit { to { transform: rotate(360deg); } }
        @keyframes marquee-track { to { transform: translateX(-50%); } }
        @keyframes text-shimmer { to { background-position: 220% center; } }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; scroll-behavior: auto !important; } .reveal { opacity: 1; transform: none; } }
    </style>
</head>

<body class="antialiased">
    <div class="bg-[#12372e] text-white/80 text-xs">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 py-2 flex justify-between gap-4"><span><i
                    class="fa-solid fa-location-dot text-amber-400 mr-2"></i>Poteran, Talango, Sumenep</span><span
                class="hidden sm:block">Portal informasi dan administrasi sekolah</span></div>
    </div>
    <header class="bg-white/95 backdrop-blur sticky top-0 z-30 border-b border-emerald-950/10">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 h-[76px] flex items-center justify-between gap-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <span class="h-11 w-11 rounded-xl bg-emerald-800 text-amber-300 grid place-items-center shadow-sm"><i
                        class="fa-solid fa-mosque text-lg"></i></span>
                <span><strong class="block text-sm text-emerald-950 tracking-tight">Yayasan PP. Fathul
                        Ulum</strong><small class="text-[10px] text-emerald-700/70 uppercase tracking-[.18em]">Poteran ·
                        Talango</small></span>
            </a>
            <nav class="hidden xl:flex items-center gap-6 text-sm font-medium text-slate-600">
                <a class="hover:text-emerald-700" href="{{ route('home') }}">Beranda</a><a
                    class="hover:text-emerald-700" href="#profil">Profil Yayasan</a><a class="hover:text-emerald-700"
                    href="{{ route('mi') }}">Unit MI</a><a class="hover:text-emerald-700"
                    href="{{ route('mts') }}">Unit MTs</a><a class="hover:text-emerald-700" href="#lokasi">Lokasi</a><a class="hover:text-emerald-700" href="#berita">Berita</a><a
                    class="hover:text-emerald-700" href="{{ route('ppdb') }}">PPDB Online</a>
            </nav>
            <a href="{{ route('login') }}"
                class="hidden sm:inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-amber-600"><i
                    class="fa-solid fa-arrow-right-to-bracket"></i> Portal Administrasi</a>
            <button type="button" data-menu-toggle aria-expanded="false" class="grid h-10 w-10 place-items-center rounded-lg border border-emerald-900/10 text-emerald-900 xl:hidden" title="Buka menu navigasi"><i class="fa-solid fa-bars"></i></button>
        </div>
        <nav id="mobile-nav" class="hidden border-t border-emerald-950/10 bg-white px-5 py-4 xl:hidden">
            <div class="grid gap-1 text-sm font-bold text-slate-600">
                <a class="rounded-lg px-3 py-2 hover:bg-emerald-50" href="{{ route('home') }}">Beranda</a>
                <a class="rounded-lg px-3 py-2 hover:bg-emerald-50" href="#profil">Profil Yayasan</a>
                <a class="rounded-lg px-3 py-2 hover:bg-emerald-50" href="{{ route('mi') }}">Unit MI</a>
                <a class="rounded-lg px-3 py-2 hover:bg-emerald-50" href="{{ route('mts') }}">Unit MTs</a>
                    <a class="rounded-lg px-3 py-2 hover:bg-emerald-50" href="#lokasi">Lokasi</a>
                <a class="rounded-lg px-3 py-2 hover:bg-emerald-50" href="#berita">Berita</a>
                <a class="mt-2 rounded-lg bg-amber-500 px-3 py-2.5 text-center text-white" href="{{ route('login') }}">Portal Administrasi</a>
            </div>
        </nav>
    </header>
    @yield('content')
    <footer class="bg-[#12372e] text-white">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 py-14 grid gap-10 md:grid-cols-[1.4fr_1fr_1fr]">
            <div>
                <div class="flex items-center gap-3 mb-4"><span
                        class="h-10 w-10 rounded-lg bg-amber-400 text-emerald-950 grid place-items-center"><i
                            class="fa-solid fa-mosque"></i></span><strong>Yayasan PP. Fathul Ulum</strong></div>
                <p class="text-sm leading-6 text-emerald-100/70 max-w-sm">Membangun generasi berilmu, berakhlak, dan
                    siap berkontribusi untuk masyarakat.</p>
            </div>
            <div>
                <h3 class="font-bold mb-4">Kunjungi Kami</h3>
                <p class="text-sm leading-6 text-emerald-100/70">{{ $siteSettings['contact_address'] ?? 'Desa Poteran, Kecamatan Talango, Kabupaten Sumenep, Jawa Timur' }}</p>
                <p class="mt-3 text-sm text-emerald-100/70"><i class="fa-solid fa-phone mr-2 text-amber-400"></i>
                    {{ $siteSettings['contact_phone'] ?? '08xx-xxxx-xxxx' }}</p>
            </div>
            <div>
                <h3 class="font-bold mb-4">Unit Pendidikan</h3><a href="{{ route('mi') }}"
                    class="block text-sm text-emerald-100/70 hover:text-white mb-2">MI Fathul Ulum</a><a
                    href="{{ route('mts') }}" class="block text-sm text-emerald-100/70 hover:text-white">MTs Fathul
                    Ulum</a>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div
                class="max-w-7xl mx-auto px-5 lg:px-8 py-5 text-xs text-emerald-100/50 flex flex-wrap justify-between gap-3">
                <span>© {{ date('Y') }} Yayasan PP. Fathul Ulum.</span><span>Portal resmi sekolah</span></div>
        </div>
    </footer>
    <script>
        const menuButton = document.querySelector('[data-menu-toggle]');
        const mobileNav = document.getElementById('mobile-nav');
        menuButton?.addEventListener('click', () => {
            const open = mobileNav.classList.toggle('hidden') === false;
            menuButton.setAttribute('aria-expanded', String(open));
            menuButton.innerHTML = `<i class="fa-solid fa-${open ? 'xmark' : 'bars'}"></i>`;
        });

        const revealItems = document.querySelectorAll('.reveal');
        if ('IntersectionObserver' in window) {
            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            }, { threshold: .12 });

            revealItems.forEach((item) => revealObserver.observe(item));
        } else {
            revealItems.forEach((item) => item.classList.add('is-visible'));
        }

        const counters = document.querySelectorAll('[data-counter]');
        counters.forEach((counter) => {
            const target = Number.parseInt(counter.dataset.counter || '0', 10);
            if (!Number.isFinite(target)) return;

            let current = 0;
            const step = Math.max(1, Math.ceil(target / 32));
            const interval = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(interval);
                }
                counter.textContent = current;
            }, 18);
        });

        document.querySelectorAll('[data-tilt]').forEach((card) => {
            card.addEventListener('pointermove', (event) => {
                const bounds = card.getBoundingClientRect();
                const x = (event.clientX - bounds.left) / bounds.width - .5;
                const y = (event.clientY - bounds.top) / bounds.height - .5;
                card.style.transform = `perspective(900px) rotateX(${y * -4}deg) rotateY(${x * 4}deg) translateY(-6px)`;
            });
            card.addEventListener('pointerleave', () => { card.style.transform = ''; });
        });
    </script>
</body>

</html>
