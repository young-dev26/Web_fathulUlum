<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        $defaultNews = [
            ['date' => '12 Juni 2026', 'tag' => 'Pengumuman', 'title' => 'Pendaftaran siswa baru tahun ajaran 2026/2027', 'excerpt' => 'Informasi jadwal, syarat, dan proses PPDB resmi akan segera dibuka untuk calon siswa baru.'],
            ['date' => '04 Juni 2026', 'tag' => 'Kegiatan', 'title' => 'Semarak prestasi akhir tahun', 'excerpt' => 'Berbagai kegiatan apresiasi, lomba, dan program pembelajaran memperkuat semangat siswa.'],
            ['date' => '28 Mei 2026', 'tag' => 'Sekolah', 'title' => 'Guru mengikuti penguatan program pembelajaran', 'excerpt' => 'Komitmen pendidik dalam meningkatkan kualitas pembelajaran dan dukungan bagi siswa.'],
        ];

        $settings = [
            'hero_badge' => SiteSetting::value('hero_badge', 'Sekolah berbasis pesantren'),
            'hero_title' => SiteSetting::value('hero_title', 'Menyalakan masa depan.'),
            'hero_description' => SiteSetting::value('hero_description', 'Pendidikan yang mengasah ilmu, menumbuhkan adab, dan memberi ruang bagi setiap anak untuk menemukan keberaniannya.'),
            'hero_image' => SiteSetting::value('hero_image', 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=2200&q=88'),
            'hero_primary_label' => SiteSetting::value('hero_primary_label', 'Mulai perjalanan'),
            'hero_primary_url' => SiteSetting::value('hero_primary_url', '/ppdb'),
            'contact_phone' => SiteSetting::value('contact_phone', '08xx-xxxx-xxxx'),
            'contact_address' => SiteSetting::value('contact_address', 'Desa Poteran, Kecamatan Talango, Kabupaten Sumenep, Jawa Timur'),
            'news' => SiteSetting::value('news', $defaultNews),
            'ppdb_title' => SiteSetting::value('ppdb_title', 'PPDB Online Fathul Ulum'),
            'ppdb_description' => SiteSetting::value('ppdb_description', 'Pendaftaran siswa baru MI dan MTs Fathul Ulum akan segera dibuka. Hubungi panitia untuk informasi jadwal dan berkas.'),
            'ppdb_status' => SiteSetting::value('ppdb_status', 'Pendaftaran segera dibuka'),
            'ppdb_cta' => SiteSetting::value('ppdb_cta', 'Hubungi panitia PPDB'),
        ];

        return view('dashboard.site-settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hero_badge' => ['required', 'string', 'max:120'],
            'hero_title' => ['required', 'string', 'max:180'],
            'hero_description' => ['required', 'string', 'max:500'],
            'hero_image' => ['required', 'url', 'max:1000'],
            'hero_primary_label' => ['required', 'string', 'max:80'],
            'hero_primary_url' => ['required', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:40'],
            'contact_address' => ['required', 'string', 'max:255'],
            'news' => ['required', 'array', 'size:3'],
            'news.*.date' => ['required', 'string', 'max:50'],
            'news.*.tag' => ['required', 'string', 'max:50'],
            'news.*.title' => ['required', 'string', 'max:180'],
            'news.*.excerpt' => ['required', 'string', 'max:500'],
            'ppdb_title' => ['required', 'string', 'max:180'],
            'ppdb_description' => ['required', 'string', 'max:500'],
            'ppdb_status' => ['required', 'string', 'max:100'],
            'ppdb_cta' => ['required', 'string', 'max:100'],
        ]);

        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value, 'type' => is_array($value) ? 'json' : 'text'],
            );
        }

        return back()->with('status', 'Pengaturan landing page berhasil diperbarui.');
    }
}