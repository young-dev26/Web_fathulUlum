@extends('layouts.dashboard')

@section('content')
<section class="p-5 lg:p-10">
    <div class="max-w-4xl">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Absensi siswa</p>
                <h1 class="mt-2 font-display text-4xl text-emerald-950">Scan QR Code</h1>
                <p class="mt-3 text-sm text-slate-500">Arahkan kamera ke QR pada kartu pelajar siswa.</p>
            </div>
            <span class="rounded-full border border-emerald-900/10 bg-white px-4 py-2 text-xs font-bold text-slate-500"><i class="fa-solid fa-wifi mr-2 text-emerald-600"></i>Scanner siap</span>
        </div>

        <div class="dashboard-card mt-8 overflow-hidden rounded-3xl bg-white lg:grid lg:grid-cols-[1.15fr_.85fr]">
            <div class="relative bg-[#062f27] p-5 lg:p-8">
                <div class="pointer-events-none absolute inset-0 opacity-20" style="background-image: linear-gradient(rgba(255,255,255,.12) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.12) 1px, transparent 1px); background-size: 32px 32px;"></div>
                <div class="relative mb-5 flex items-center justify-between text-white">
                    <span class="text-[10px] font-bold uppercase tracking-[.22em] text-amber-300">Live scanner</span>
                    <span class="flex items-center gap-2 text-xs text-emerald-100/70"><i class="fa-solid fa-circle text-[7px] text-emerald-300 animate-pulse"></i> Kamera aktif</span>
                </div>
                <div class="relative mx-auto max-w-lg overflow-hidden rounded-2xl border border-white/20 bg-slate-950 p-2 shadow-2xl shadow-black/20">
                    <div class="pointer-events-none absolute inset-8 z-10 rounded-xl border border-amber-300/70">
                        <span class="absolute -left-px -top-px h-8 w-8 border-l-4 border-t-4 border-amber-300"></span><span class="absolute -right-px -top-px h-8 w-8 border-r-4 border-t-4 border-amber-300"></span><span class="absolute -bottom-px -left-px h-8 w-8 border-b-4 border-l-4 border-amber-300"></span><span class="absolute -bottom-px -right-px h-8 w-8 border-b-4 border-r-4 border-amber-300"></span><span class="scan-line absolute left-2 right-2 top-1/2 h-px bg-amber-300 shadow-[0_0_14px_3px_rgba(252,211,77,.75)]"></span>
                    </div>
                    <div id="reader" class="overflow-hidden rounded-xl"></div>
                </div>
                    <div class="relative mx-auto mt-4 flex max-w-lg justify-center">
                        <button id="restart-scan" type="button" class="scan-action hidden items-center justify-center gap-2 rounded-xl bg-amber-300 px-5 py-3 text-sm font-bold text-emerald-950">
                            <i class="fa-solid fa-rotate-right"></i><span>Aktifkan kamera lagi</span>
                        </button>
                    </div>
                <p class="relative mt-5 text-center text-xs text-emerald-100/60">Posisikan QR tepat di dalam garis kuning</p>
            </div>
            <div class="p-5 lg:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div><p class="text-xs font-bold uppercase tracking-[.18em] text-amber-600">Kehadiran</p><h2 class="mt-2 font-display text-3xl text-emerald-950">Catat dengan cepat.</h2></div>
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-amber-100 text-amber-700"><i class="fa-solid fa-bolt"></i></span>
                </div>
                <div id="scan-status" class="mt-7 flex items-center gap-3 rounded-2xl bg-emerald-50 p-4 text-sm text-emerald-800"><i class="fa-solid fa-camera text-emerald-600"></i><span>Meminta akses kamera...</span></div>
                <div id="scan-result" class="mt-4 hidden rounded-2xl p-4 text-sm font-bold"></div>
                <div class="mt-8 border-t border-slate-100 pt-6">
                    <div class="flex items-center justify-between gap-3"><div><p class="text-xs font-bold uppercase tracking-[.16em] text-amber-600">Tanpa kartu?</p><h3 class="mt-1 font-bold text-emerald-950">Cari siswa manual</h3></div><i class="fa-solid fa-user-magnifying-glass text-xl text-emerald-700"></i></div>
                    <div class="relative mt-4"><i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i><input id="student-search" type="search" autocomplete="off" placeholder="Ketik nama atau NISN..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-9 pr-3 text-sm focus:bg-white"></div>
                    <div id="student-results" class="mt-2 hidden divide-y divide-slate-100 overflow-hidden rounded-xl border border-slate-200 bg-white"></div>
                    <p class="mt-3 text-xs leading-5 text-slate-400">Pilih siswa dari hasil pencarian untuk mencatat kehadiran tanpa QR.</p>
                </div>
                <div class="mt-6 space-y-4 border-t border-slate-100 pt-6"><div class="flex gap-3"><span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-800">01</span><p class="text-sm leading-5 text-slate-500">Pastikan kartu siswa memiliki QR yang terlihat jelas.</p></div><div class="flex gap-3"><span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-amber-100 text-xs font-bold text-amber-800">02</span><p class="text-sm leading-5 text-slate-500">Jaga jarak kamera agar kode masuk ke dalam bingkai.</p></div><div class="flex gap-3"><span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-slate-100 text-xs font-bold text-slate-600">03</span><p class="text-sm leading-5 text-slate-500">Satu scan mencatat kehadiran untuk hari ini.</p></div></div>
            </div>
        </div>
    </div>
</section>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    const resultBox = document.getElementById('scan-result');
    const statusBox = document.getElementById('scan-status');
    const studentSearch = document.getElementById('student-search');
    const studentResults = document.getElementById('student-results');
    const restartButton = document.getElementById('restart-scan');
    let scanner = null;
    let scannerRunning = false;
    let locked = false;
    let searchTimer;

    function setStatus(message, tone = 'emerald') {
        statusBox.className = `mt-5 flex items-center gap-3 rounded-2xl p-4 text-sm ${tone === 'rose' ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-800'}`;
        statusBox.innerHTML = `<i class="fa-solid ${tone === 'rose' ? 'fa-circle-exclamation' : 'fa-camera'}"></i><span>${message}</span>`;
    }

    function onScanSuccess(decodedText) {
        if (locked) return;
        locked = true;
        setStatus('QR terbaca. Menyimpan kehadiran...');
        fetch('{{ route('dashboard.scan.store') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ qr_code_key: decodedText })
        })
            .then(async (response) => ({ ok: response.ok, data: await response.json() }))
            .then(({ ok, data }) => {
                resultBox.className = `mt-4 rounded-2xl p-4 text-sm font-bold ${ok ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'}`;
                resultBox.textContent = data.message || 'Respons scanner tidak dikenali.';
                resultBox.classList.remove('hidden');
                setStatus(ok ? 'Kehadiran berhasil dicatat.' : 'QR sudah diproses atau belum dikenali.', ok ? 'emerald' : 'rose');
                if (ok && window.speechSynthesis) {
                    const utterance = new SpeechSynthesisUtterance(`Berhasil. ${data.message}`);
                    utterance.lang = 'id-ID';
                    const indonesianVoice = window.speechSynthesis.getVoices().find((voice) => voice.lang.toLowerCase().startsWith('id'));
                    if (indonesianVoice) utterance.voice = indonesianVoice;
                    window.speechSynthesis.cancel();
                    window.speechSynthesis.speak(utterance);
                }
                setTimeout(() => { locked = false; resultBox.classList.add('hidden'); }, 3500);
            })
            .catch(() => { locked = false; setStatus('Tidak dapat terhubung ke server. Coba lagi.', 'rose'); });
    }

    function onScanFailure() {}

    function announceSuccess(message) {
        if (!window.speechSynthesis) return;
        const utterance = new SpeechSynthesisUtterance(`Berhasil. ${message}`);
        utterance.lang = 'id-ID';
        const voice = window.speechSynthesis.getVoices().find((item) => item.lang.toLowerCase().startsWith('id'));
        if (voice) utterance.voice = voice;
        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(utterance);
    }

    async function recordManualAttendance(studentId, studentName) {
        if (locked) return;
        locked = true;
        setStatus(`Mencatat kehadiran ${studentName}...`);
        try {
            const response = await fetch('{{ route('dashboard.scan.manual') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: JSON.stringify({ student_id: studentId }) });
            const data = await response.json();
            resultBox.className = `mt-4 rounded-2xl p-4 text-sm font-bold ${response.ok ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'}`;
            resultBox.textContent = data.message || 'Kehadiran tidak dapat dicatat.';
            resultBox.classList.remove('hidden');
            setStatus(response.ok ? 'Kehadiran berhasil dicatat.' : 'Siswa sudah tercatat atau data tidak valid.', response.ok ? 'emerald' : 'rose');
            if (response.ok) announceSuccess(data.message);
        } catch (error) {
            setStatus('Tidak dapat terhubung ke server. Coba lagi.', 'rose');
        } finally {
            locked = false;
        }
    }

    studentSearch.addEventListener('input', () => {
        clearTimeout(searchTimer);
        const query = studentSearch.value.trim();
        if (query.length < 2) { studentResults.classList.add('hidden'); studentResults.innerHTML = ''; return; }
        searchTimer = setTimeout(async () => {
            const response = await fetch(`{{ route('dashboard.scan.students') }}?q=${encodeURIComponent(query)}`, { headers: { Accept: 'application/json' } });
            const data = await response.json();
            studentResults.innerHTML = data.students.length ? data.students.map((student) => `<button type="button" data-student-id="${student.id}" data-student-name="${student.nama_lengkap}" class="flex w-full items-center justify-between gap-3 px-3 py-3 text-left text-sm hover:bg-emerald-50"><span><strong class="block text-emerald-950">${student.nama_lengkap}</strong><small class="text-slate-500">${student.nisn} · ${student.unit.toUpperCase()} kelas ${student.kelas}</small></span><i class="fa-solid fa-circle-check text-emerald-600"></i></button>`).join('') : '<p class="p-4 text-sm text-slate-500">Siswa tidak ditemukan.</p>';
            studentResults.classList.remove('hidden');
            studentResults.querySelectorAll('[data-student-id]').forEach((button) => button.addEventListener('click', () => { recordManualAttendance(button.dataset.studentId, button.dataset.studentName); studentSearch.value = button.dataset.studentName; studentResults.classList.add('hidden'); }));
        }, 250);
    });

    async function startScanner() {
        if (scannerRunning) return;
        restartButton.classList.add('hidden');
        restartButton.classList.remove('inline-flex');
        setStatus('Meminta izin kamera...');

        try {
            const cameras = await Html5Qrcode.getCameras();
            if (!cameras.length) throw new Error('Kamera tidak ditemukan.');
            scanner = scanner || new Html5Qrcode('reader');
            await scanner.start(cameras[0].id, { fps: 10, qrbox: { width: 250, height: 250 } }, onScanSuccess, onScanFailure);
            scannerRunning = true;
            setStatus('Kamera aktif. Arahkan QR ke dalam bingkai.');
        } catch (error) {
            restartButton.classList.remove('hidden');
            restartButton.classList.add('inline-flex');
            setStatus(error.message || 'Kamera tidak dapat digunakan. Periksa izin browser.', 'rose');
        }
    }

    restartButton.addEventListener('click', startScanner);
    if (!window.Html5Qrcode) {
        setStatus('Library scanner gagal dimuat. Periksa koneksi internet.', 'rose');
    } else {
        startScanner();
    }
</script>
@endsection
