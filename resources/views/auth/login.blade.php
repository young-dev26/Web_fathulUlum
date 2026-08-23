@extends('layouts.app')
@section('content')
	<style>
		.auth-stage { isolation: isolate; }
		.auth-stage::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 12% 15%, rgba(245,158,11,.18), transparent 28%), radial-gradient(circle at 88% 78%, rgba(6,95,70,.14), transparent 30%); z-index: -2; }
		.auth-grid { background-image: linear-gradient(rgba(255,255,255,.09) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.09) 1px, transparent 1px); background-size: 32px 32px; }
		.auth-intro { z-index: 2; transition: transform .7s cubic-bezier(.2,.8,.2,1); }
		.auth-intro::after { content: ''; position: absolute; inset: -20% -60%; background: linear-gradient(105deg, transparent 38%, rgba(255,255,255,.08) 50%, transparent 62%); transform: translateX(-30%); animation: auth-sheen 7s ease-in-out infinite; pointer-events: none; }
		.auth-slide-links { position: relative; z-index: 3; display: flex; gap: 1.25rem; align-items: center; }
		.auth-slide-link { color: rgba(255,255,255,.62); font-size: .8rem; font-weight: 700; transition: color .25s ease, transform .25s ease; }
		.auth-slide-link:hover, .auth-slide-link.is-active { color: #fcd34d; transform: translateY(-2px); }
		.auth-slide-divider { width: 1px; height: 1rem; background: rgba(255,255,255,.25); }
		.auth-form-panel { position: relative; z-index: 1; }
		.auth-shell.is-signup .auth-intro { transform: translateX(100%); }
		.auth-spark { position: absolute; border: 1px solid rgba(6,95,70,.12); border-radius: 999px; z-index: -1; animation: auth-float 8s ease-in-out infinite; }
		.auth-spark-one { width: 17rem; height: 17rem; top: 8%; left: -8rem; }
		.auth-spark-two { width: 24rem; height: 24rem; right: -11rem; bottom: -8rem; animation-delay: -3s; }
		.auth-tabs { position: relative; display: grid; grid-template-columns: 1fr 1fr; border-bottom: 1px solid #e2e8f0; }
		.auth-tab { position: relative; z-index: 1; padding: .75rem .5rem; color: #94a3b8; font-size: .875rem; font-weight: 700; transition: color .3s ease; }
		.auth-tab.is-active { color: #065f46; }
		.auth-tab-indicator { position: absolute; bottom: -1px; left: 0; width: 50%; height: 3px; border-radius: 3px 3px 0 0; background: #f59e0b; transition: transform .45s cubic-bezier(.2,.8,.2,1); }
		.auth-tab:nth-child(2).is-active ~ .auth-tab-indicator { transform: translateX(100%); }
		.auth-panels { display: grid; overflow: hidden; }
		.auth-panel { grid-area: 1 / 1; opacity: 0; pointer-events: none; transform: translateX(26px); transition: opacity .35s ease, transform .45s cubic-bezier(.2,.8,.2,1); }
		.auth-panel.is-active { opacity: 1; pointer-events: auto; transform: translateX(0); }
		.auth-panel.is-active > * { animation: auth-rise .55s both; }
		.auth-submit { transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease; }
		.auth-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(6,95,70,.16); }
		@keyframes auth-float { 0%,100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-18px) rotate(8deg); } }
		@keyframes auth-sheen { 0%,35% { transform: translateX(-30%); opacity: 0; } 50% { opacity: 1; } 70%,100% { transform: translateX(30%); opacity: 0; } }
		@keyframes auth-rise { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
		@media (min-width: 768px) {
			.auth-shell { max-width: 900px; min-height: 500px; display: block; }
			.auth-intro { position: absolute; inset: 0 auto 0 0; display: flex !important; width: 50%; }
			.auth-form-panel { width: 100%; min-height: 500px; margin-left: 0; padding: 2rem 2.5rem; }
			.auth-form-panel > .mb-8 { display: none; }
			.auth-tabs { display: none; }
			.auth-panels { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; overflow: visible; }
			.auth-panel, .auth-panel.is-active { grid-area: auto; opacity: 1; pointer-events: auto; transform: none; }
			.auth-panel[data-auth-panel="signup"] { border-left: 1px solid #e2e8f0; padding-left: 2rem; }
		}
		@media (max-width: 767px) { .auth-form-panel { min-height: 540px; } }
		@media (prefers-reduced-motion: reduce) { .auth-spark, .auth-panel, .auth-tab-indicator, .auth-submit { animation: none; transition: none; } }
	</style>
	<section class="auth-stage relative overflow-hidden bg-[#eaf2ed] px-5 py-14 sm:py-20">
		<div class="auth-spark auth-spark-one"></div>
		<div class="auth-spark auth-spark-two"></div>
		<div class="auth-shell relative mx-auto grid max-w-5xl overflow-hidden rounded-[2rem] bg-white shadow-2xl shadow-emerald-950/15 lg:grid-cols-[.88fr_1.12fr]" data-auth-shell>
			<aside class="auth-intro relative hidden overflow-hidden bg-emerald-950 p-10 text-white lg:flex lg:flex-col lg:justify-between">
				<div class="auth-grid absolute inset-0 opacity-30"></div>
				<div class="relative">
					<span class="grid h-14 w-14 place-items-center rounded-2xl bg-amber-400 text-xl text-emerald-950 shadow-lg shadow-amber-400/20"><i class="fa-solid fa-mosque"></i></span>
					<p class="mt-10 text-xs font-bold uppercase tracking-[.25em] text-amber-300">Fathul Ulum</p>
					<h2 class="mt-4 max-w-xs font-display text-4xl leading-tight">Satu pintu untuk tumbuh bersama.</h2>
					<p class="mt-5 max-w-sm text-sm leading-7 text-emerald-100/70">Kelola informasi sekolah dan temukan ruang belajar yang berarti untuk setiap anak.</p>
				</div>
				<div class="relative">
					<div class="auth-slide-links mb-5">
						<a href="#login-panel" class="auth-slide-link is-active" data-auth-tab="login" role="tab" aria-selected="true">Masuk</a>
						<span class="auth-slide-divider"></span>
						<a href="#signup-panel" class="auth-slide-link" data-auth-tab="signup" role="tab" aria-selected="false">Daftar</a>
					</div>
					<div class="flex items-center gap-3 text-sm text-emerald-100/70"><span class="h-2 w-2 rounded-full bg-amber-400"></span>Portal resmi Yayasan PP. Fathul Ulum</div>
				</div>
			</aside>
			<div class="auth-form-panel p-7 sm:p-10">
				<div class="mb-8 flex items-center justify-between gap-4">
					<div><p class="text-xs font-bold uppercase tracking-[.2em] text-amber-600">Selamat datang</p><h1 class="mt-2 font-display text-3xl text-emerald-950">Portal Administrasi</h1></div>
					<span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-emerald-800 text-amber-300 lg:hidden"><i class="fa-solid fa-lock"></i></span>
				</div>
				<div class="auth-panels">
					<div id="login-panel" class="auth-panel is-active" data-auth-panel="login">
						<p class="mt-5 text-sm text-slate-500">Pilih peran dan gunakan identitas akun yang terdaftar.</p>
						@if ($errors->any())<div class="mt-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>@endif
						<form class="mt-8 space-y-5" action="{{ route('auth.login') }}" method="post">@csrf
							<label class="block"><span class="text-sm font-bold text-slate-700">Masuk sebagai</span><select name="role" id="login-role" required class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 outline-none focus:border-emerald-600"><option value="admin" @selected(old('role', 'admin') === 'admin')>Admin</option><option value="staff_tu" @selected(old('role') === 'staff_tu')>Staff TU</option><option value="guru" @selected(old('role') === 'guru')>Guru</option><option value="siswa" @selected(old('role') === 'siswa')>Siswa</option><option value="orang_tua" @selected(old('role') === 'orang_tua')>Wali Siswa</option></select></label>
							<label class="block"><span id="login-identifier-label" class="text-sm font-bold text-slate-700">Email</span><input id="login-identifier" type="text" name="identifier" value="{{ old('identifier') }}" required autofocus autocomplete="username" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 outline-none focus:border-emerald-600" placeholder="admin@fathululum.sch.id"></label>
							<label class="block"><span class="text-sm font-bold text-slate-700">Kata sandi</span><input type="password" name="password" required autocomplete="current-password" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 outline-none focus:border-emerald-600" placeholder="Masukkan kata sandi"></label>
							<label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-emerald-700"> Ingat saya</label>
							<button class="auth-submit w-full rounded-lg bg-emerald-800 px-5 py-3 font-bold text-white hover:bg-emerald-700">Masuk ke portal <i class="fa-solid fa-arrow-right ml-1"></i></button>
						</form>
						<p class="mt-6 text-center text-xs text-slate-400">Gunakan akun yang diberikan oleh administrator yayasan.</p>
					</div>
					<div id="signup-panel" class="auth-panel" data-auth-panel="signup" aria-hidden="true">
						<div class="mt-8 rounded-2xl bg-amber-50 p-6"><span class="grid h-12 w-12 place-items-center rounded-xl bg-amber-400 text-emerald-950"><i class="fa-solid fa-user-plus"></i></span><h2 class="mt-5 font-display text-2xl text-emerald-950">Belum punya akses?</h2><p class="mt-2 text-sm leading-6 text-slate-600">Pendaftaran akun portal diberikan oleh administrator. Untuk calon siswa, mulai dari formulir PPDB online.</p><a href="{{ route('ppdb.register') }}" class="auth-submit mt-6 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-amber-500 px-5 py-3 font-bold text-white hover:bg-amber-600">Daftar PPDB <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i></a></div>
						<p class="mt-6 text-center text-xs text-slate-400">Setelah terdaftar, akun portal akan diproses oleh admin sekolah.</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script>
		const loginRole = document.querySelector('#login-role');
		const loginIdentifierLabel = document.querySelector('#login-identifier-label');
		const loginIdentifier = document.querySelector('#login-identifier');
		const loginFields = { admin: ['Email', 'admin@fathululum.sch.id'], staff_tu: ['NIP', 'Masukkan NIP Staff TU'], guru: ['NIP', 'Masukkan NIP guru'], siswa: ['NIS', 'Masukkan NIS siswa'], orang_tua: ['Nomor handphone', '08xxxxxxxxxx'] };
		loginRole?.addEventListener('change', () => { const field = loginFields[loginRole.value] || loginFields.admin; loginIdentifierLabel.textContent = field[0]; loginIdentifier.placeholder = field[1]; loginIdentifier.type = loginRole.value === 'admin' ? 'email' : 'text'; });
		loginRole?.dispatchEvent(new Event('change'));
		document.querySelectorAll('[data-auth-tab]').forEach((tab) => {
			tab.addEventListener('click', (event) => {
				event.preventDefault();
				const selected = tab.dataset.authTab;
				document.querySelector('[data-auth-shell]')?.classList.toggle('is-signup', selected === 'signup');
				document.querySelectorAll('[data-auth-tab]').forEach((item) => { const active = item === tab; item.classList.toggle('is-active', active); item.setAttribute('aria-selected', String(active)); });
				document.querySelectorAll('[data-auth-panel]').forEach((panel) => { const active = panel.dataset.authPanel === selected; panel.classList.toggle('is-active', active); panel.setAttribute('aria-hidden', String(!active)); });
			});
		});
	</script>
@endsection
