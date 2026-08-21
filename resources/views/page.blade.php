@extends('layouts.app')

@section('content')
    <section class="bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-700 text-white">
        <div class="mx-auto max-w-7xl px-5 py-20 sm:px-6 lg:px-8 lg:py-24">
            <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-300">{{ $eyebrow }}</p>
            <h1 class="mt-4 max-w-3xl font-display text-4xl leading-tight sm:text-5xl">{{ $title }}</h1>
            <p class="mt-6 max-w-2xl text-base leading-7 text-emerald-50/80 sm:text-lg">{{ $description }}</p>
            @isset($ppdb_status)
                <div class="mt-7 flex flex-wrap items-center gap-3"><span class="rounded-full bg-amber-300 px-3 py-1.5 text-xs font-bold text-emerald-950"><i class="fa-solid fa-bullhorn mr-1"></i>{{ $ppdb_status }}</span><a href="{{ route('ppdb.register') }}" class="rounded-xl bg-white px-4 py-2.5 text-xs font-bold text-emerald-900">{{ $ppdb_cta }} <i class="fa-solid fa-arrow-right ml-1"></i></a></div>
            @endisset
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-20 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-3xl bg-white p-7 shadow-sm ring-1 ring-slate-200">
                <span class="grid h-12 w-12 place-items-center rounded-2xl bg-amber-100 text-xl text-amber-700"><i class="fa-solid fa-circle-info"></i></span>
                <h2 class="mt-5 font-display text-2xl text-emerald-950">Informasi sekolah</h2>
                <p class="mt-3 text-sm leading-6 text-slate-500">Halaman ini menjadi pusat informasi profil, kegiatan, dan perkembangan pendidikan {{ $title }}.</p>
            </div>

            <div class="rounded-3xl bg-emerald-800 p-7 text-white shadow-sm">
                <span class="grid h-12 w-12 place-items-center rounded-2xl bg-white/10 text-xl text-amber-300"><i class="fa-solid fa-users"></i></span>
                <h2 class="mt-5 font-display text-2xl">Komunitas belajar</h2>
                <p class="mt-3 text-sm leading-6 text-emerald-50/70">Siswa, guru, dan orang tua terhubung dalam ekosistem pendidikan yang kuat dan mendukung perkembangan pribadi.</p>
            </div>

            <div class="rounded-3xl bg-amber-100 p-7 text-emerald-950 shadow-sm">
                <span class="grid h-12 w-12 place-items-center rounded-2xl bg-white text-xl text-amber-700"><i class="fa-solid fa-arrow-right"></i></span>
                <h2 class="mt-5 font-display text-2xl">Kembali ke beranda</h2>
                <a href="{{ route('home') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-emerald-800">
                    Jelajahi portal
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
@endsection
