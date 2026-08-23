@extends('layouts.app')

@section('content')
    <article class="mx-auto max-w-3xl px-5 py-16 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}#berita" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-700"><i class="fa-solid fa-arrow-left"></i>Kembali ke berita</a>
        <div class="mt-8 border-b border-slate-200 pb-8">
            <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">{{ $article['tag'] }} · {{ $article['date'] }}</p>
            <h1 class="mt-4 font-display text-4xl leading-tight text-emerald-950 sm:text-5xl">{{ $article['title'] }}</h1>
            <p class="mt-5 text-lg leading-8 text-slate-600">{{ $article['excerpt'] }}</p>
        </div>
        <div class="prose prose-emerald mt-8 max-w-none text-sm leading-7 text-slate-600">
            <p>{{ $article['excerpt'] }}</p>
            <p>Yayasan PP. Fathul Ulum terus menghadirkan informasi dan kegiatan pendidikan yang membantu siswa, orang tua, dan masyarakat mengikuti perkembangan sekolah.</p>
        </div>
        <a href="{{ $article['tag'] === 'Pengumuman' ? route('ppdb') : route('home') . '#profil' }}" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-emerald-800 px-5 py-3 text-sm font-bold text-white">{{ $article['tag'] === 'Pengumuman' ? 'Lihat informasi PPDB' : 'Kenali sekolah kami' }}<i class="fa-solid fa-arrow-right"></i></a>
    </article>
@endsection