@extends('layouts.app')

@section('content')
    <section class="relative min-h-[680px] overflow-hidden bg-[#062f27] text-white lg:min-h-[760px]">
        <img src="{{ $siteSettings['hero_image'] }}" alt="Gedung sekolah" class="hero-photo pointer-events-none absolute inset-0 h-full w-full object-cover opacity-55">
        <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(3,38,31,.98)_0%,rgba(3,52,42,.84)_42%,rgba(3,52,42,.35)_100%)]"></div>
        <div class="hero-grid pointer-events-none absolute inset-0 opacity-35"></div>
        <div class="hero-sheen pointer-events-none absolute -inset-y-20 left-1/3 w-1/3 rotate-12 bg-amber-200/20 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(251,191,36,0.22),transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(255,255,255,0.12),transparent_22%)]"></div>
        <div class="hero-orbit pointer-events-none absolute -right-24 top-20 h-[420px] w-[420px] rounded-full border border-amber-300/25 border-dashed lg:right-16 lg:top-24"></div>
        <div class="pointer-events-none absolute right-16 top-36 h-3 w-3 rounded-full bg-amber-300 shadow-[0_0_0_8px_rgba(252,211,77,.15),0_0_36px_12px_rgba(252,211,77,.45)] lg:right-80"></div>

        <div class="relative mx-auto max-w-7xl px-5 pb-16 pt-24 sm:px-6 lg:px-8 lg:pb-24 lg:pt-28">
            <div class="grid items-center gap-12 lg:grid-cols-[1fr_.78fr]">
                <div class="reveal">
                    <span class="inline-flex items-center gap-2 rounded-full border border-amber-200/30 bg-amber-200/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[.25em] text-amber-200 backdrop-blur">
                        <span class="h-2 w-2 animate-pulse rounded-full bg-amber-300"></span>
                        {{ $siteSettings['hero_badge'] }}
                    </span>

                    <h1 class="mt-7 max-w-3xl font-display text-5xl leading-[.95] tracking-tight sm:text-6xl lg:text-[6.8rem]">
                        {{ $siteSettings['hero_title'] }}
                    </h1>

                    <p class="mt-7 max-w-xl text-base leading-7 text-emerald-50/80 sm:text-lg">
                        {{ $siteSettings['hero_description'] }}
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ $siteSettings['hero_primary_url'] }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold text-emerald-950 shadow-lg shadow-amber-500/20 transition hover:-translate-y-0.5 hover:bg-amber-300">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            {{ $siteSettings['hero_primary_label'] }}
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/25 bg-white/5 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                            <i class="fa-solid fa-lock"></i>
                            Masuk portal
                        </a>
                    </div>

                    <div class="mt-9 flex flex-wrap items-center gap-x-7 gap-y-3 text-sm text-emerald-50/75">
                        <span class="inline-flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> MI & MTs</span>
                        <span class="inline-flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Komunitas aktif</span>
                        <span class="inline-flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Berbasis akhlak</span>
                    </div>
                </div>

                <div class="reveal relative lg:justify-self-end">
                    <div class="relative mx-auto max-w-md rounded-[32px] border border-white/30 bg-white/10 p-3 shadow-2xl shadow-black/30 backdrop-blur-md">
                        <div class="relative overflow-hidden rounded-[24px] bg-amber-300 p-1">
                            <div class="relative overflow-hidden rounded-[20px] bg-emerald-950 p-6 text-white">
                                <div class="flex items-center justify-between border-b border-white/15 pb-5"><span class="text-[10px] font-bold uppercase tracking-[.24em] text-amber-300">Fathul Ulum / 01</span><i class="fa-solid fa-arrow-up-right-from-square text-amber-300"></i></div>
                                <p class="mt-14 text-sm text-emerald-100/60">Satu ruang untuk</p>
                                <h2 class="mt-2 font-display text-4xl leading-tight">Belajar.<br>Beradab.<br><span class="text-amber-300">Bertumbuh.</span></h2>
                                <div class="mt-12 flex items-end justify-between"><span class="text-xs text-emerald-100/60">MI & MTs Poteran<br>Talango, Sumenep</span><span class="grid h-12 w-12 place-items-center rounded-full bg-amber-300 text-emerald-950"><i class="fa-solid fa-arrow-down rotate-[-45deg]"></i></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 overflow-hidden border-t border-white/10 bg-emerald-950/30 py-3 backdrop-blur-sm">
            <div class="marquee-track flex gap-8 text-[10px] font-bold uppercase tracking-[.25em] text-emerald-100/65"><span>Ilmu yang hidup</span><i class="fa-solid fa-asterisk text-amber-300"></i><span>Adab yang tumbuh</span><i class="fa-solid fa-asterisk text-amber-300"></i><span>Masa depan yang siap</span><i class="fa-solid fa-asterisk text-amber-300"></i><span>Ilmu yang hidup</span><i class="fa-solid fa-asterisk text-amber-300"></i><span>Adab yang tumbuh</span><i class="fa-solid fa-asterisk text-amber-300"></i><span>Masa depan yang siap</span><i class="fa-solid fa-asterisk text-amber-300"></i></div>
        </div>
    </section>

    <section class="relative z-10 mx-auto max-w-7xl -mt-8 px-5 sm:px-6 lg:px-8">
        <div class="reveal grid gap-3 rounded-2xl bg-white p-3 shadow-2xl shadow-emerald-950/10 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($stats as $stat)
                <div class="rounded-2xl {{ $stat['tone'] === 'emerald' ? 'bg-emerald-800 text-white' : ($stat['tone'] === 'amber' ? 'bg-amber-100 text-emerald-950' : 'bg-slate-50 text-emerald-950') }} p-5">
                    <div class="flex items-center justify-between gap-3">
                        <i class="fa-solid {{ $stat['icon'] }} {{ $stat['tone'] === 'emerald' ? 'text-amber-300' : 'text-emerald-700' }}"></i>
                        <span class="text-[10px] uppercase tracking-[.18em] {{ $stat['tone'] === 'emerald' ? 'text-emerald-100/70' : 'text-slate-500' }}">Aktif</span>
                    </div>
                    <strong data-counter="{{ $stat['value'] }}" class="mt-5 block font-display text-3xl {{ $stat['tone'] === 'emerald' ? 'text-white' : 'text-emerald-950' }}">0</strong>
                    <p class="mt-2 text-xs {{ $stat['tone'] === 'emerald' ? 'text-emerald-100/80' : 'text-slate-500' }}">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="section-glow mx-auto max-w-7xl px-5 pt-14 sm:px-6 lg:px-8 lg:pt-20">
        <div class="reveal mb-7 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Akses cepat</p>
                <h2 class="mt-2 font-display text-3xl text-emerald-950 sm:text-4xl">Semua kebutuhan sekolah, satu tempat.</h2>
            </div>
            <p class="max-w-sm text-sm leading-6 text-slate-500">Pilih layanan yang ingin Anda jelajahi dan temukan informasi penting Fathul Ulum dengan lebih cepat.</p>
        </div>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['route' => 'mi', 'icon' => 'fa-book-open', 'label' => 'Unit MI', 'text' => 'Pendidikan dasar yang hangat dan berkarakter.', 'tone' => 'bg-emerald-800 text-white'],
                ['route' => 'mts', 'icon' => 'fa-compass', 'label' => 'Unit MTs', 'text' => 'Tumbuh mandiri, siap menghadapi masa depan.', 'tone' => 'bg-amber-400 text-emerald-950'],
                ['route' => 'ppdb.register', 'icon' => 'fa-pen-to-square', 'label' => 'PPDB Online', 'text' => 'Mulai perjalanan pendidikan bersama kami.', 'tone' => 'bg-[#e7f1ea] text-emerald-950'],
                ['route' => 'login', 'icon' => 'fa-shield-halved', 'label' => 'Portal Administrasi', 'text' => 'Akses data sekolah secara aman dan terarah.', 'tone' => 'bg-slate-100 text-emerald-950'],
            ] as $service)
                <a href="{{ route($service['route']) }}" data-tilt class="interactive-card reveal group relative overflow-hidden rounded-2xl {{ $service['tone'] }} p-5">
                    <span class="absolute -right-5 -top-5 h-20 w-20 rounded-full border-[10px] border-white/15"></span>
                    <span class="icon-bounce relative grid h-10 w-10 place-items-center rounded-xl bg-white/20 text-lg"><i class="fa-solid {{ $service['icon'] }}"></i></span>
                    <h3 class="relative mt-5 font-display text-2xl">{{ $service['label'] }}</h3>
                    <p class="relative mt-2 text-xs leading-5 opacity-70">{{ $service['text'] }}</p>
                    <span class="relative mt-5 inline-flex items-center gap-2 text-xs font-bold">Jelajahi <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i></span>
                </a>
            @endforeach
        </div>
    </section>

    <section id="profil" class="section-glow mx-auto max-w-7xl px-5 py-20 sm:px-6 lg:px-8 lg:py-24">
        <div class="reveal mb-10 flex flex-wrap items-end justify-between gap-5">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Pendidikan terpadu</p>
                <h2 class="mt-2 font-display text-4xl text-emerald-950">Kenali unit sekolah kami</h2>
            </div>
            <p class="max-w-sm text-sm leading-6 text-slate-500">
                Dua jenjang pendidikan dengan pendekatan yang menyenangkan, religius, dan membangun karakter sejak dini.
            </p>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            @foreach ([
                ['route' => 'mi', 'label' => '01 / Dasar', 'title' => 'MI Fathul Ulum', 'description' => 'Landasan membaca, menulis, berakhlak, dan tumbuh percaya diri sejak usia dini.', 'bg' => 'bg-emerald-800', 'text' => 'text-white', 'accent' => 'text-amber-300'],
                ['route' => 'mts', 'label' => '02 / Lanjutan', 'title' => 'MTs Fathul Ulum', 'description' => 'Pendampingan siswa menuju kemandirian berpikir, kreativitas, dan kesiapan menghadapi tantangan masa depan.', 'bg' => 'bg-amber-400', 'text' => 'text-emerald-950', 'accent' => 'text-emerald-900'],
                ['route' => 'ppdb', 'label' => '03 / Pendaftaran', 'title' => 'PPDB Online', 'description' => 'Informasi penerimaan siswa baru yang jelas, cepat, dan mudah diakses oleh orang tua dan calon siswa.', 'bg' => 'bg-slate-100', 'text' => 'text-emerald-950', 'accent' => 'text-amber-700'],
            ] as $item)
                <a href="{{ route($item['route']) }}" data-tilt class="interactive-card reveal group relative overflow-hidden rounded-3xl {{ $item['bg'] }} p-7 shadow-lg shadow-slate-200">
                    <span class="absolute -bottom-10 -right-10 h-40 w-40 rounded-full border-[20px] border-white/15"></span>
                    <div class="relative">
                        <span class="text-[10px] font-bold uppercase tracking-[.2em] {{ $item['accent'] }}">{{ $item['label'] }}</span>
                        <h3 class="mt-5 font-display text-3xl {{ $item['text'] }}">{{ $item['title'] }}</h3>
                        <p class="mt-3 max-w-sm text-sm leading-6 {{ $item['text'] === 'text-white' ? 'text-emerald-50/75' : 'text-emerald-950/75' }}">
                            {{ $item['description'] }}
                        </p>
                        <span class="mt-8 inline-flex items-center gap-2 text-sm font-bold {{ $item['accent'] }}">
                            Lihat selengkapnya
                            <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="section-glow bg-gradient-to-b from-slate-50 to-white">
        <div class="mx-auto max-w-7xl px-5 py-20 sm:px-6 lg:px-8">
            <div class="reveal mb-10 text-center">
                <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Kenapa kami</p>
                <h2 class="mt-2 font-display text-4xl text-emerald-950">Lingkungan belajar yang membangun masa depan</h2>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                @foreach ($highlights as $highlight)
                    <div data-tilt class="interactive-card reveal rounded-3xl border border-slate-200 bg-white p-7 shadow-sm">
                        <span class="icon-bounce grid h-12 w-12 place-items-center rounded-2xl bg-emerald-100 text-xl text-emerald-800">
                            <i class="fa-solid {{ $highlight['icon'] }}"></i>
                        </span>
                        <h3 class="mt-5 font-display text-2xl text-emerald-950">{{ $highlight['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-500">{{ $highlight['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="lokasi" class="section-glow overflow-hidden bg-[#dcebe2]">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-5 py-20 sm:px-6 lg:grid-cols-[.78fr_1.22fr] lg:px-8 lg:py-24">
            <div class="reveal relative z-10">
                <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-700">Temukan kami</p>
                <h2 class="mt-3 max-w-md font-display text-4xl leading-tight text-emerald-950 sm:text-5xl">Tempat ilmu dan karakter bertumbuh.</h2>
                <p class="mt-5 max-w-md text-sm leading-7 text-slate-600">
                    Kunjungi lingkungan pendidikan Yayasan PP. Fathul Ulum di Poteran, Talango, Sumenep. Peta di samping dapat digeser, diperbesar, dan dibuka langsung untuk memulai perjalanan.
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="https://www.google.com/maps/search/?api=1&query=Yayasan+PP+Fathul+Ulum+Poteran+Talango" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-xl bg-emerald-800 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-950/15 transition hover:-translate-y-0.5 hover:bg-emerald-700">
                        <i class="fa-solid fa-diamond-turn-right"></i>
                        Buka petunjuk arah
                    </a>
                    <span class="inline-flex items-center gap-2 rounded-xl border border-emerald-900/10 bg-white/70 px-4 py-3 text-sm font-semibold text-emerald-900">
                        <i class="fa-solid fa-location-dot text-amber-600"></i>
                        Poteran, Talango
                    </span>
                </div>
                <div class="mt-8 flex items-center gap-3 text-xs font-semibold text-emerald-900/65">
                    <span class="grid h-9 w-9 place-items-center rounded-full bg-amber-400 text-emerald-950"><i class="fa-solid fa-school"></i></span>
                    <span>MI & MTs Fathul Ulum<br><small class="font-normal text-slate-500">Kabupaten Sumenep, Jawa Timur</small></span>
                </div>
            </div>

            <div data-tilt class="interactive-card reveal relative min-h-[410px] overflow-hidden rounded-[30px] border-[10px] border-white bg-[#b9d7c4] shadow-2xl shadow-emerald-950/15">
                <div class="pointer-events-none absolute inset-0 z-10 bg-gradient-to-br from-white/10 via-transparent to-emerald-950/10"></div>
                <div class="pointer-events-none absolute left-5 top-5 z-20 rounded-xl bg-white/90 px-3 py-2 text-[10px] font-bold uppercase tracking-[.15em] text-emerald-900 shadow-lg backdrop-blur">
                    <i class="fa-solid fa-compass mr-1 text-amber-600"></i> Lokasi sekolah
                </div>
                <iframe
                    title="Peta lokasi Yayasan PP. Fathul Ulum Poteran Talango"
                    class="h-[410px] w-full grayscale-[.15] contrast-[.95]"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps?q=Yayasan+PP+Fathul+Ulum+Poteran+Talango&output=embed">
                </iframe>
                <div class="map-float pointer-events-none absolute bottom-7 left-1/2 z-20 -translate-x-1/2">
                    <div class="map-pulse absolute left-1/2 top-1/2 h-12 w-12 -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-400/50"></div>
                    <div class="relative grid h-11 w-11 place-items-center rounded-full rounded-br-sm bg-amber-400 text-emerald-950 shadow-xl shadow-amber-950/30 rotate-45">
                        <i class="fa-solid fa-school -rotate-45 text-sm"></i>
                    </div>
                </div>
                <svg class="pointer-events-none absolute inset-0 z-10 h-full w-full opacity-70" viewBox="0 0 600 410" preserveAspectRatio="none" aria-hidden="true">
                    <path class="map-route" d="M40 325 C150 280, 180 345, 275 270 S420 150, 560 92" fill="none" stroke="#f59e0b" stroke-linecap="round" stroke-width="3" />
                </svg>
                <div class="pointer-events-none absolute bottom-4 left-4 z-20 rounded-xl bg-emerald-950/90 px-3 py-2 text-xs text-white shadow-xl backdrop-blur">
                    <i class="fa-solid fa-map-pin mr-1 text-amber-300"></i> Yayasan PP. Fathul Ulum
                </div>
            </div>
        </div>
    </section>

    <section id="berita" class="bg-[#eaf2ed]">
        <div class="mx-auto max-w-7xl px-5 py-20 sm:px-6 lg:px-8">
            <div class="reveal mb-8 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Kabar terbaru</p>
                    <h2 class="mt-2 font-display text-4xl text-emerald-950">Berita & pengumuman</h2>
                </div>
                <a href="{{ route('ppdb') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-700">
                    Lihat PPDB
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                @foreach ($news as $item)
                    <article data-tilt class="interactive-card reveal rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <p class="text-[11px] font-bold uppercase tracking-[.18em] text-amber-600">{{ $item['tag'] }} · {{ $item['date'] }}</p>
                        <h3 class="mt-4 font-display text-2xl leading-tight text-emerald-950">{{ $item['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-500">{{ $item['excerpt'] }}</p>
                        <a href="{{ route('ppdb') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-emerald-700">
                            Baca selengkapnya
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-20 sm:px-6 lg:px-8">
        <div class="reveal relative overflow-hidden rounded-[32px] bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-700 p-8 text-white shadow-2xl shadow-emerald-950/20 lg:p-12">
            <div class="footer-orbit pointer-events-none absolute -right-28 -top-36 h-80 w-80 rounded-full border border-amber-300/30 border-dashed"></div>
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-300">Siap bergabung?</p>
                    <h2 class="mt-2 font-display text-4xl">Bangun masa depan bersama Fathul Ulum</h2>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('ppdb') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold text-emerald-950">Daftar sekarang</a>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/5 px-5 py-3 text-sm font-bold text-white">Portal login</a>
                </div>
            </div>
        </div>
    </section>
@endsection
