@extends('layouts.dashboard')

@section('content')
    @php
        $role = $user->effectiveRole();
        $name = $user->nama_lengkap ?? $user->name;
        $credential = match ($role) { 'admin' => 'email', 'staff_tu', 'guru' => 'nip', 'siswa' => 'nis', 'orang_tua' => 'nomor_hp', default => 'email' };
        $credentialLabel = match ($credential) { 'email' => 'Email', 'nip' => 'NIP', 'nis' => 'NIS', default => 'Nomor handphone' };
    @endphp
    <section class="p-5 lg:p-10">
        <div class="max-w-3xl">
            <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Pengaturan akun</p>
            <h1 class="mt-2 font-display text-4xl text-emerald-950">Profil saya</h1>
            <p class="mt-2 text-sm text-slate-500">Pastikan identitas login dan informasi pribadi Anda selalu terbaru.</p>
            @if ($errors->any())<div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>@endif
            <form action="{{ route('dashboard.profile.update') }}" method="post" class="dashboard-card mt-8 space-y-5 rounded-2xl bg-white p-6 sm:p-8">@csrf @method('put')
                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="block text-sm font-bold text-slate-700">Nama lengkap<input name="nama_lengkap" value="{{ old('nama_lengkap', $name) }}" required class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 font-normal"></label>
                    <label class="block text-sm font-bold text-slate-700">{{ $credential }}<input name="{{ $credential }}" value="{{ old($credential, $user->{$credential} ?? ($user->email ?? '')) }}" required class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 font-normal" @disabled($credential === 'email')></label>
                </div>
                @if ($credential !== 'email')
                    <p class="-mt-2 text-xs text-slate-400">{{ $credentialLabel }} digunakan saat masuk ke portal.</p>
                    <label class="block text-sm font-bold text-slate-700">Email pemulihan<input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 font-normal"></label>
                @endif
                @if ($credential === 'email')<input type="hidden" name="email" value="{{ old('email', $user->email) }}">@endif
                <div class="grid gap-5 border-t border-slate-100 pt-5 sm:grid-cols-2">
                    <label class="block text-sm font-bold text-slate-700">Kata sandi baru<input type="password" name="password" minlength="8" autocomplete="new-password" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 font-normal" placeholder="Kosongkan jika tetap"></label>
                    <label class="block text-sm font-bold text-slate-700">Ulangi kata sandi<input type="password" name="password_confirmation" minlength="8" autocomplete="new-password" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 font-normal"></label>
                </div>
                <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-800 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700"><i class="fa-solid fa-floppy-disk"></i>Simpan perubahan</button>
            </form>
        </div>
    </section>
@endsection