<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stats = [
            ['value' => '384', 'label' => 'Siswa aktif', 'icon' => 'fa-child', 'tone' => 'emerald'],
            ['value' => '42', 'label' => 'Guru & tenaga pendidik', 'icon' => 'fa-chalkboard-user', 'tone' => 'white'],
            ['value' => '24', 'label' => 'Rombel', 'icon' => 'fa-school', 'tone' => 'amber'],
            ['value' => '8', 'label' => 'Ekstrakurikuler', 'icon' => 'fa-medal', 'tone' => 'white'],
        ];

        $highlights = [
            ['icon' => 'fa-book-open-reader', 'title' => 'Pembelajaran berkarakter', 'description' => 'Menggabungkan ilmu umum dan akhlak mulia dalam setiap aktivitas belajar.'],
            ['icon' => 'fa-users-gear', 'title' => 'Lingkungan suportif', 'description' => 'Komunitas sekolah yang hangat, inklusif, dan mendorong tumbuh kembang siswa.'],
            ['icon' => 'fa-chart-line', 'title' => 'Potensi terus diasah', 'description' => 'Siswa didorong aktif, kreatif, dan siap menghadapi tantangan masa depan.'],
        ];

        $news = SiteSetting::value('news', [
            ['date' => '12 Juni 2026', 'tag' => 'Pengumuman', 'title' => 'Pendaftaran siswa baru tahun ajaran 2026/2027', 'excerpt' => 'Informasi jadwal, syarat, dan proses PPDB resmi akan segera dibuka untuk calon siswa baru.'],
            ['date' => '04 Juni 2026', 'tag' => 'Kegiatan', 'title' => 'Semarak prestasi akhir tahun', 'excerpt' => 'Berbagai kegiatan apresiasi, lomba, dan program pembelajaran memperkuat semangat siswa.'],
            ['date' => '28 Mei 2026', 'tag' => 'Sekolah', 'title' => 'Guru mengikuti penguatan program pembelajaran', 'excerpt' => 'Komitmen pendidik dalam meningkatkan kualitas pembelajaran dan dukungan bagi siswa.'],
        ]);

        $siteSettings = [
            'hero_badge' => SiteSetting::value('hero_badge', 'Sekolah berbasis pesantren'),
            'hero_title' => SiteSetting::value('hero_title', 'Menyalakan masa depan.'),
            'hero_description' => SiteSetting::value('hero_description', 'Pendidikan yang mengasah ilmu, menumbuhkan adab, dan memberi ruang bagi setiap anak untuk menemukan keberaniannya.'),
            'hero_image' => SiteSetting::value('hero_image', 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=2200&q=88'),
            'hero_primary_label' => SiteSetting::value('hero_primary_label', 'Mulai perjalanan'),
            'hero_primary_url' => SiteSetting::value('hero_primary_url', '/ppdb'),
            'contact_phone' => SiteSetting::value('contact_phone', '08xx-xxxx-xxxx'),
            'contact_address' => SiteSetting::value('contact_address', 'Desa Poteran, Kecamatan Talango, Kabupaten Sumenep, Jawa Timur'),
            'ppdb_title' => SiteSetting::value('ppdb_title', 'PPDB Online Fathul Ulum'),
            'ppdb_description' => SiteSetting::value('ppdb_description', 'Pendaftaran siswa baru MI dan MTs Fathul Ulum akan segera dibuka. Hubungi panitia untuk informasi jadwal dan berkas.'),
            'ppdb_status' => SiteSetting::value('ppdb_status', 'Pendaftaran segera dibuka'),
            'ppdb_cta' => SiteSetting::value('ppdb_cta', 'Hubungi panitia PPDB'),
        ];

        return view('home', compact('stats', 'highlights', 'news', 'siteSettings'));
    }

    public function mi(): View
    {
        return view('page', [
            'eyebrow' => 'Unit Madrasah Ibtidaiyah',
            'title' => 'MI Fathul Ulum',
            'description' => 'Ruang tumbuh anak-anak Poteran untuk mengenal ilmu, adab, dan kemandirian sejak dini.',
            'accent' => 'emerald',
        ]);
    }

    public function mts(): View
    {
        return view('page', [
            'eyebrow' => 'Unit Madrasah Tsanawiyah',
            'title' => 'MTs Fathul Ulum',
            'description' => 'Mendampingi siswa remaja menyiapkan masa depan dengan ilmu pengetahuan, karakter, dan kepercayaan diri.',
            'accent' => 'amber',
        ]);
    }

    public function ppdb(): View
    {
        return view('page', [
            'eyebrow' => 'Penerimaan Peserta Didik Baru',
            'title' => SiteSetting::value('ppdb_title', 'PPDB Online Fathul Ulum'),
            'description' => SiteSetting::value('ppdb_description', 'Pendaftaran siswa baru MI dan MTs Fathul Ulum akan segera dibuka. Hubungi panitia untuk informasi jadwal dan berkas.'),
            'accent' => 'amber',
            'ppdb_status' => SiteSetting::value('ppdb_status', 'Pendaftaran segera dibuka'),
            'ppdb_cta' => SiteSetting::value('ppdb_cta', 'Hubungi panitia PPDB'),
        ]);
    }

    public function login(): View
    {
        return view('auth.login');
    }
}
