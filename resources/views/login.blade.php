@extends('layouts.app')
@section('content')
    <section class="min-h-[620px] bg-[#eaf2ed] px-5 py-20">
        <div class="mx-auto max-w-md rounded-2xl bg-white p-8 shadow-xl shadow-emerald-950/10">
            <div class="text-center"><span
                    class="mx-auto grid h-12 w-12 place-items-center rounded-xl bg-emerald-800 text-amber-300"><i
                        class="fa-solid fa-lock"></i></span>
                <h1 class="mt-5 font-display text-3xl text-emerald-950">Portal Administrasi</h1>
                <p class="mt-2 text-sm text-slate-500">Masuk sebagai admin, guru, atau siswa.</p>
            </div>
            @if ($errors->any())
                <div class="mt-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}
                </div>
            @endif
            <form class="mt-8 space-y-5" action="{{ route('auth.login') }}" method="post">@csrf<label class="block"><span
                        class="text-sm font-bold text-slate-700">Email</span><input type="email" name="email"
                        value="{{ old('email') }}" required autofocus autocomplete="email"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 outline-none focus:border-emerald-600"
                        placeholder="nama@fathululum.sch.id"></label><label class="block"><span
                        class="text-sm font-bold text-slate-700">Kata sandi</span><input type="password" name="password"
                        required autocomplete="current-password"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 outline-none focus:border-emerald-600"
                        placeholder="Masukkan kata sandi"></label><label
                    class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remember"
                        value="1" class="rounded border-slate-300 text-emerald-700"> Ingat saya</label><button
                    class="w-full rounded-lg bg-emerald-800 px-5 py-3 font-bold text-white hover:bg-emerald-700">Masuk ke
                    portal <i class="fa-solid fa-arrow-right ml-1"></i></button></form>
            <p class="mt-6 text-center text-xs text-slate-400">Gunakan akun yang diberikan oleh administrator yayasan.</p>
        </div>
    </section>
@endsection
