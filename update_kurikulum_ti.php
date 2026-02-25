<?php
// Script untuk update kurikulum Teknologi Informasi ke database
// Jalankan dengan: php update_kurikulum_ti.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Prodi;

// Kurikulum lengkap untuk Teknologi Informasi berdasarkan data terbaru
$kurikulumTI = [
    // Semester 1 - Total 19 SKS
    ['semester' => 1, 'kode' => 'TI101', 'nama' => 'Logika Matematika', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'TI102', 'nama' => 'Berpikir Komputasional & Pengenalan Pemrograman', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'TI103', 'nama' => 'Kalkulus', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'TI104', 'nama' => 'Pengantar Teknologi Informasi', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'TI105', 'nama' => 'Pancasila', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'TI106', 'nama' => 'Bahasa Indonesia', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'TI107', 'nama' => 'Pendidikan Karakter', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 2 - Total 18 SKS + 1 SKS Praktikum
    ['semester' => 2, 'kode' => 'TI201', 'nama' => 'Statistika & Analistik Data', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'TI202', 'nama' => 'Aljabar Linear dan Matriks', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TI101'],
    ['semester' => 2, 'kode' => 'TI203', 'nama' => 'Algoritma Pemrograman', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TI102'],
    ['semester' => 2, 'kode' => 'TI203P', 'nama' => 'Algoritma Pemrograman (Praktikum)', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => 'TI102'],
    ['semester' => 2, 'kode' => 'TI204', 'nama' => 'Matematika Diskrit', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'TI205', 'nama' => 'Bahasa Inggris I', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'TI206', 'nama' => 'Pemeliharaan dan Administrasi Teknologi Informasi', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'TI207', 'nama' => 'Pendidikan Agama', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 3 - Total 19 SKS + 1 SKS Praktikum
    ['semester' => 3, 'kode' => 'TI301', 'nama' => 'Pengantar Teori Peluang', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'TI302', 'nama' => 'Struktur Data', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TI203'],
    ['semester' => 3, 'kode' => 'TI302P', 'nama' => 'Struktur Data (Praktikum)', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => 'TI203'],
    ['semester' => 3, 'kode' => 'TI303', 'nama' => 'Sistem Multimedia', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'TI304', 'nama' => 'Manajemen Layanan Teknologi Informasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'TI305', 'nama' => 'Wawasan Global TIK', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'TI306', 'nama' => 'Keprofesian Teknologi Informasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'TI307', 'nama' => 'Organisasi dan Arsitektur Komputer', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 4 - Total 17 SKS + 3 SKS Praktikum
    ['semester' => 4, 'kode' => 'TI401', 'nama' => 'Jaringan Komputer', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'TI401P', 'nama' => 'Jaringan Komputer (Praktikum)', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'TI402', 'nama' => 'Sistem Manejemn Basis Data', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TI302'],
    ['semester' => 4, 'kode' => 'TI402P', 'nama' => 'Sistem Manejemn Basis Data (Praktikum)', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => 'TI302'],
    ['semester' => 4, 'kode' => 'TI403', 'nama' => 'Interaksi Manusia dan Komputer', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'TI404', 'nama' => 'Kewirausahaan', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'TI405', 'nama' => 'Sistem Operasi', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'TI405P', 'nama' => 'Sistem Operasi (Praktikum)', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'TI406', 'nama' => 'Sistem Cerdas', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'TI407', 'nama' => 'Teknologi Informasi Untuk Masyarakat', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 5 - Total 17 SKS + 2 SKS Praktikum
    ['semester' => 5, 'kode' => 'TI501', 'nama' => 'Keamanan Siber', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'TI502', 'nama' => 'Pengalaman Pengguna', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TI403'],
    ['semester' => 5, 'kode' => 'TI503', 'nama' => 'Pemrograman Platform & IOT', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'TI504', 'nama' => 'Pemrograman Web', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'TI504P', 'nama' => 'Pemrograman Web (Praktikum)', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'TI505', 'nama' => 'Pemrograman Berbasis Objek', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TI302'],
    ['semester' => 5, 'kode' => 'TI505P', 'nama' => 'Pemrograman Berbasis Objek (Praktikum)', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => 'TI302'],
    ['semester' => 5, 'kode' => 'TI506', 'nama' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 6 - Total 19 SKS
    ['semester' => 6, 'kode' => 'TI601', 'nama' => 'Aplikasi Perangkat Bergerak', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'TI602', 'nama' => 'Komputasi Awan dan Virtualisasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'TI603', 'nama' => 'Arsitektur Integrasi Sistem', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'TI604', 'nama' => 'Manajemen Proyek', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'TI605', 'nama' => 'Pengujian Penetrasi dan Etika Peretasan', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TI501'],
    ['semester' => 6, 'kode' => 'TI606', 'nama' => 'Kewarganegaraan', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'TI607', 'nama' => 'Metodologi Penelitian dan Tata Tulis Ilmiah', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 7 - Total 19 SKS
    ['semester' => 7, 'kode' => 'TI701', 'nama' => 'Penulisan Proposal', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => 'TI607'],
    ['semester' => 7, 'kode' => 'TI702', 'nama' => 'Kerja Praktik', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'TI703', 'nama' => 'MK Pilihan Wajib I', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'TI704', 'nama' => 'MK Pilihan Wajib II', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'TI705', 'nama' => 'MK Pilihan I', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'TI706', 'nama' => 'Bahasa Inggris II', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => 'TI205'],
    ['semester' => 7, 'kode' => 'TI707', 'nama' => 'Proyek Teknologi Informasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 8 - Total 10 SKS
    ['semester' => 8, 'kode' => 'TI801', 'nama' => 'MK Pilihan II', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 8, 'kode' => 'TI802', 'nama' => 'MK Pilihan III', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 8, 'kode' => 'TI803', 'nama' => 'Tugas Akhir', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'TI701'],
];

// Cari prodi Teknologi Informasi
$prodiTI = Prodi::where('nama_prodi', 'LIKE', '%Teknologi Informasi%')->first();

if($prodiTI) {
    $prodiTI->kurikulum_data = $kurikulumTI;
    $prodiTI->total_sks = array_sum(array_column($kurikulumTI, 'sks'));
    $prodiTI->jumlah_semester = 8;
    $prodiTI->save();
    
    echo "✅ Kurikulum berhasil ditambahkan ke prodi: {$prodiTI->nama_prodi}\n";
    echo "📚 Total: " . count($kurikulumTI) . " mata kuliah\n";
    echo "🎓 Total SKS: {$prodiTI->total_sks}\n";
} else {
    echo "❌ Prodi Teknologi Informasi tidak ditemukan\n";
}
