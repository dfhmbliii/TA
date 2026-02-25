<?php
// Script untuk update kurikulum Desain Komunikasi Visual ke database
// Jalankan dengan: php update_kurikulum_dkv.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Prodi;

// Kurikulum lengkap untuk Desain Komunikasi Visual berdasarkan data terbaru
$kurikulumDKV = [
    // Semester 1 - Total 19 SKS
    ['semester' => 1, 'kode' => 'DKV101', 'nama' => 'Budaya Nusantara', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'DKV102', 'nama' => 'Bahasa Inggris untuk Seni dan Desain', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'DKV103', 'nama' => 'Bahasa Inggris untuk Seni dan Desain', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'DKV104', 'nama' => 'Komunikasi dan Media Literasi', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'DKV105', 'nama' => 'Rupa Dasar', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'DKV106', 'nama' => 'Menggambar Bentuk', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'DKV107', 'nama' => 'Komputer Grafis', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'DKV108', 'nama' => 'Internalisasi Budaya dan Pembentukan Karakter', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 2 - Total 18 SKS
    ['semester' => 2, 'kode' => 'DKV201', 'nama' => 'Estetika dan Sejarah Komunikasi Visual', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'DKV202', 'nama' => 'Menggambar Figuratif', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'DKV106'],
    ['semester' => 2, 'kode' => 'DKV203', 'nama' => 'Tipografi Dasar', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'DKV204', 'nama' => 'Komputer Multimedia', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'DKV107'],
    ['semester' => 2, 'kode' => 'DKV205', 'nama' => 'Teknologi Grafika', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'DKV206', 'nama' => 'Studio DKV 1 - Literasi Visual dan Ikonografi', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 3 - Total 20 SKS
    ['semester' => 3, 'kode' => 'DKV301', 'nama' => 'Pengantar Branding', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'DKV302', 'nama' => 'Copywriting', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'DKV303', 'nama' => 'Semiotika dan Bahasa Visual', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'DKV304', 'nama' => 'Tipografi dan Tata Letak', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'DKV203'],
    ['semester' => 3, 'kode' => 'DKV305', 'nama' => 'Fotografi Dasar', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'DKV306', 'nama' => 'Ilustrasi Dasar', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'DKV202'],
    ['semester' => 3, 'kode' => 'DKV307', 'nama' => 'Studio DKV 2 - Desain Informasi', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'DKV206'],
    
    // Semester 4 - Total 22 SKS
    ['semester' => 4, 'kode' => 'DKV401', 'nama' => 'Design Thinking', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'DKV402', 'nama' => 'Visual Storytelling', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'DKV403', 'nama' => 'Studio DKV 3 - Identitas Brand', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'DKV307'],
    ['semester' => 4, 'kode' => 'DKV404', 'nama' => 'Copywriting 2', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'DKV302'],
    ['semester' => 4, 'kode' => 'DKV405', 'nama' => 'Advertising and Media', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'DKV406', 'nama' => 'Market Research and Consumer Behaviour', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'DKV407', 'nama' => 'Manajemen Periklanan', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'DKV408', 'nama' => 'Bahasa Indonesia', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 5 - Total 20 SKS
    ['semester' => 5, 'kode' => 'DKV501', 'nama' => 'Metodologi Desain', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'DKV502', 'nama' => 'Desain Sosial', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'DKV503', 'nama' => 'Portofolio and Visual Presentation', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'DKV504', 'nama' => 'Studio CA 4 - Digital Media', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'DKV403'],
    ['semester' => 5, 'kode' => 'DKV505', 'nama' => 'Print Media', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'DKV506', 'nama' => 'Non-Commercial Advertising', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'DKV405'],
    ['semester' => 5, 'kode' => 'DKV507', 'nama' => 'Creative Content', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 6 - Total 18 SKS
    ['semester' => 6, 'kode' => 'DKV601', 'nama' => 'Kerja Profesi', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'DKV602', 'nama' => 'Cultural Studies', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'DKV603', 'nama' => 'Manajemen Proyek', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'DKV604', 'nama' => 'Studio CA 5 - Brand Communication', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'DKV504'],
    ['semester' => 6, 'kode' => 'DKV605', 'nama' => 'Metodologi Penelitian', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'DKV606', 'nama' => 'Creative Strategy and Art Directing', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'DKV607', 'nama' => 'MK Pilihan Peminatan adv 1', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    
    // Semester 7 - Total 18 SKS
    ['semester' => 7, 'kode' => 'DKV701', 'nama' => 'Etika Industri Desain', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'DKV702', 'nama' => 'Seminar', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'DKV703', 'nama' => 'MK Pilihan Peminatan adv 2', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'DKV704', 'nama' => 'Bahasa Inggris', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'DKV705', 'nama' => 'Agama', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'DKV706', 'nama' => 'Kewarganegaraan', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 8 - Total 10 SKS
    ['semester' => 8, 'kode' => 'DKV801', 'nama' => 'Tugas Akhir / Skripsi', 'sks' => 6, 'kategori' => 'wajib', 'prasyarat' => 'DKV702'],
    ['semester' => 8, 'kode' => 'DKV802', 'nama' => 'Kewirausahaan', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 8, 'kode' => 'DKV803', 'nama' => 'Pancasila', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
];

// Cari prodi Desain Komunikasi Visual
$prodiDKV = Prodi::where('nama_prodi', 'LIKE', '%Desain Komunikasi Visual%')
                 ->orWhere('nama_prodi', 'LIKE', '%DKV%')
                 ->first();

if($prodiDKV) {
    $prodiDKV->kurikulum_data = $kurikulumDKV;
    $prodiDKV->total_sks = array_sum(array_column($kurikulumDKV, 'sks'));
    $prodiDKV->jumlah_semester = 8;
    $prodiDKV->save();
    
    echo "✅ Kurikulum berhasil ditambahkan ke prodi: {$prodiDKV->nama_prodi}\n";
    echo "📚 Total: " . count($kurikulumDKV) . " mata kuliah\n";
    echo "🎓 Total SKS: {$prodiDKV->total_sks}\n";
} else {
    echo "❌ Prodi Desain Komunikasi Visual tidak ditemukan\n";
    echo "📋 Daftar prodi yang tersedia:\n";
    $allProdis = Prodi::all();
    foreach($allProdis as $p) {
        echo "   - {$p->nama_prodi}\n";
    }
}
