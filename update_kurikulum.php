<?php
// Script untuk update kurikulum data ke database
// Jalankan dengan: php update_kurikulum.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Prodi;

// Sample kurikulum data untuk Sistem Informasi
$kurikulumSI = [
    // Semester 1
    ['semester' => 1, 'kode' => 'UNW00101', 'nama' => 'Pendidikan Pancasila', 'sks' => 2, 'kategori' => 'mkwu', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'UNW00102', 'nama' => 'Pendidikan Agama', 'sks' => 2, 'kategori' => 'mkwu', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'SIF00101', 'nama' => 'Algoritma dan Pemrograman', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'SIF00102', 'nama' => 'Matematika Diskrit', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'SIF00103', 'nama' => 'Pengantar Sistem Informasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'SIF00104', 'nama' => 'Pengantar Teknologi Informasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 2
    ['semester' => 2, 'kode' => 'UNW00201', 'nama' => 'Bahasa Indonesia', 'sks' => 2, 'kategori' => 'mkwu', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'UNW00202', 'nama' => 'Bahasa Inggris', 'sks' => 2, 'kategori' => 'mkwu', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'SIF00201', 'nama' => 'Struktur Data', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'SIF00101'],
    ['semester' => 2, 'kode' => 'SIF00202', 'nama' => 'Basis Data', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'SIF00203', 'nama' => 'Sistem Digital', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'SIF00204', 'nama' => 'Statistika', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 3
    ['semester' => 3, 'kode' => 'UNW00301', 'nama' => 'Kewarganegaraan', 'sks' => 2, 'kategori' => 'mkwu', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'SIF00301', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'SIF00201'],
    ['semester' => 3, 'kode' => 'SIF00302', 'nama' => 'Analisis dan Perancangan Sistem', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'SIF00202'],
    ['semester' => 3, 'kode' => 'SIF00303', 'nama' => 'Jaringan Komputer', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'SIF00304', 'nama' => 'Sistem Operasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'SIF00305', 'nama' => 'Manajemen Proyek SI', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 4
    ['semester' => 4, 'kode' => 'SIF00401', 'nama' => 'Rekayasa Perangkat Lunak', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'SIF00302'],
    ['semester' => 4, 'kode' => 'SIF00402', 'nama' => 'Pemrograman Web', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'SIF00301'],
    ['semester' => 4, 'kode' => 'SIF00403', 'nama' => 'Interaksi Manusia dan Komputer', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'SIF00404', 'nama' => 'Kecerdasan Buatan', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'SIF00405', 'nama' => 'E-Business', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 5
    ['semester' => 5, 'kode' => 'SIF00501', 'nama' => 'Keamanan Sistem Informasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'SIF00502', 'nama' => 'Data Mining', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SIF00202'],
    ['semester' => 5, 'kode' => 'SIF00503', 'nama' => 'Sistem Enterprise', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'SIF00504', 'nama' => 'Tata Kelola TI', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'SIF00505', 'nama' => 'Mobile Programming', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'SIF00506', 'nama' => 'Big Data', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    
    // Semester 6
    ['semester' => 6, 'kode' => 'SIF00601', 'nama' => 'Audit Sistem Informasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'SIF00602', 'nama' => 'Business Intelligence', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SIF00502'],
    ['semester' => 6, 'kode' => 'SIF00603', 'nama' => 'Metodologi Penelitian', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'SIF00604', 'nama' => 'Kerja Praktek', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'SIF00605', 'nama' => 'Cloud Computing', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'SIF00606', 'nama' => 'IoT', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    
    // Semester 7
    ['semester' => 7, 'kode' => 'SIF00701', 'nama' => 'Kewirausahaan', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'SIF00702', 'nama' => 'Etika Profesi', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'SIF00703', 'nama' => 'Skripsi 1', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'SIF00603'],
    ['semester' => 7, 'kode' => 'SIF00704', 'nama' => 'Machine Learning', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'SIF00705', 'nama' => 'Blockchain', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    
    // Semester 8
    ['semester' => 8, 'kode' => 'SIF00801', 'nama' => 'Skripsi 2', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'SIF00703'],
];

// Cari prodi Sistem Informasi
$prodiSI = Prodi::where('nama_prodi', 'LIKE', '%Sistem Informasi%')->first();

if($prodiSI) {
    $prodiSI->kurikulum_data = $kurikulumSI;
    $prodiSI->total_sks = array_sum(array_column($kurikulumSI, 'sks'));
    $prodiSI->jumlah_semester = 8;
    $prodiSI->save();
    
    echo "✅ Kurikulum berhasil ditambahkan ke prodi: {$prodiSI->nama_prodi}\n";
    echo "📚 Total: " . count($kurikulumSI) . " mata kuliah\n";
    echo "🎓 Total SKS: {$prodiSI->total_sks}\n";
} else {
    echo "❌ Prodi Sistem Informasi tidak ditemukan\n";
}
