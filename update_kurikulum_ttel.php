<?php
// Script untuk update kurikulum Teknik Telekomunikasi ke database
// Jalankan dengan: php update_kurikulum_ttel.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Prodi;

// Kurikulum lengkap untuk Teknik Telekomunikasi berdasarkan data terbaru
$kurikulumTTEL = [
    // Semester 1 - Total 18 SKS
    ['semester' => 1, 'kode' => 'TTEL101', 'nama' => 'Pancasila', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'TTEL102', 'nama' => 'Pengenalan Teknik Telekomunikasi', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'TTEL103', 'nama' => 'Fisika 1', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'TTEL104', 'nama' => 'Kalkulus 1', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'TTEL105', 'nama' => 'Praktikum Fisika 1', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'TTEL106', 'nama' => 'Kimia', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'TTEL107', 'nama' => 'Pengantar Rekayasa dan Desain', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'TTEL108', 'nama' => 'Internalisasi Budaya dan Pembentukan Karakter', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 2 - Total 19 SKS
    ['semester' => 2, 'kode' => 'TTEL201', 'nama' => 'Agama', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'TTEL202', 'nama' => 'Matematika Diskret', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'TTEL203', 'nama' => 'Algoritma dan Pemrograman', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'TTEL204', 'nama' => 'Fisika 2', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TTEL103'],
    ['semester' => 2, 'kode' => 'TTEL205', 'nama' => 'Kalkulus 2', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TTEL104'],
    ['semester' => 2, 'kode' => 'TTEL206', 'nama' => 'Praktikum Algoritma dan Pemrograman', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'TTEL207', 'nama' => 'Praktikum Fisika 2', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => 'TTEL105'],
    ['semester' => 2, 'kode' => 'TTEL208', 'nama' => 'Aljabar Linier', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 3 - Total 18 SKS
    ['semester' => 3, 'kode' => 'TTEL301', 'nama' => 'Praktikum Teknik Telekomunikasi 1', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'TTEL302', 'nama' => 'Pemrograman Python', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => 'TTEL203'],
    ['semester' => 3, 'kode' => 'TTEL303', 'nama' => 'Rangkaian Listrik', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'TTEL304', 'nama' => 'Variabel Kompleks', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TTEL205'],
    ['semester' => 3, 'kode' => 'TTEL305', 'nama' => 'Persamaan Diferensial', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TTEL205'],
    ['semester' => 3, 'kode' => 'TTEL306', 'nama' => 'Probabilitas dan Statistika', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'TTEL307', 'nama' => 'Jaringan dan Trafik Telekomunikasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 4 - Total 19 SKS
    ['semester' => 4, 'kode' => 'TTEL401', 'nama' => 'Praktikum Teknik Telekomunikasi 2', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => 'TTEL301'],
    ['semester' => 4, 'kode' => 'TTEL402', 'nama' => 'Teknik Digital', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'TTEL403', 'nama' => 'Elektronika', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TTEL303'],
    ['semester' => 4, 'kode' => 'TTEL404', 'nama' => 'Artificial Intelligence dan Big Data', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'TTEL405', 'nama' => 'Elektromagnetika', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'TTEL406', 'nama' => 'Pengolahan Sinyal Waktu Kontinu', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TTEL304'],
    ['semester' => 4, 'kode' => 'TTEL407', 'nama' => 'Jaringan Komunikasi Data', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TTEL307'],
    
    // Semester 5 - Total 19 SKS
    ['semester' => 5, 'kode' => 'TTEL501', 'nama' => 'Praktikum Teknik Telekomunikasi 3', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => 'TTEL401'],
    ['semester' => 5, 'kode' => 'TTEL502', 'nama' => 'Sistem Komunikasi 1', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'TTEL503', 'nama' => 'Manajemen Proyek', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'TTEL504', 'nama' => 'Elektromagnetika Telekomunikasi', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => 'TTEL405'],
    ['semester' => 5, 'kode' => 'TTEL505', 'nama' => 'Pengolahan Sinyal Waktu Diskret', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TTEL406'],
    ['semester' => 5, 'kode' => 'TTEL506', 'nama' => 'Keamanan Data dan Blockchain', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'TTEL507', 'nama' => 'Kewirausahaan', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'TTEL508', 'nama' => 'Bahasa Indonesia', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 6 - Total 19 SKS
    ['semester' => 6, 'kode' => 'TTEL601', 'nama' => 'Elektronika Rf', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TTEL403'],
    ['semester' => 6, 'kode' => 'TTEL602', 'nama' => 'Mikroprosesor Dan Iot', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TTEL402'],
    ['semester' => 6, 'kode' => 'TTEL603', 'nama' => 'Kerja Praktek / Kkn', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'TTEL604', 'nama' => 'Praktikum Teknik Telekomunikasi 4', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => 'TTEL501'],
    ['semester' => 6, 'kode' => 'TTEL605', 'nama' => 'Sistem Komunikasi 2', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TTEL502'],
    ['semester' => 6, 'kode' => 'TTEL606', 'nama' => 'Teknologi Antena', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => 'TTEL504'],
    ['semester' => 6, 'kode' => 'TTEL607', 'nama' => 'Sistem Komunikasi Optik', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TTEL502'],
    ['semester' => 6, 'kode' => 'TTEL608', 'nama' => 'Studium General', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 7 - Total 19 SKS
    ['semester' => 7, 'kode' => 'TTEL701', 'nama' => 'Literasi Manusia', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'TTEL702', 'nama' => 'Sistem Komunikasi Seluler', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'TTEL605'],
    ['semester' => 7, 'kode' => 'TTEL703', 'nama' => 'Proposal Tugas Akhir', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'TTEL704', 'nama' => 'MK Pilihan 1', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'TTEL705', 'nama' => 'MK Pilihan 2', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'TTEL706', 'nama' => 'MK Pilihan 3', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'TTEL707', 'nama' => 'MK Pilihan 4', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    
    // Semester 8 - Total 14 SKS
    ['semester' => 8, 'kode' => 'TTEL801', 'nama' => 'Bahasa Inggris', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 8, 'kode' => 'TTEL802', 'nama' => 'Tugas Akhir', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'TTEL703'],
    ['semester' => 8, 'kode' => 'TTEL803', 'nama' => 'Kewarganegaraan', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 8, 'kode' => 'TTEL804', 'nama' => 'Mk Pilihan 5', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 8, 'kode' => 'TTEL805', 'nama' => 'Mk Pilihan 6', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
];

// Cari prodi Teknik Telekomunikasi
$prodiTTEL = Prodi::where('nama_prodi', 'LIKE', '%Teknik Telekomunikasi%')
                  ->orWhere('nama_prodi', 'LIKE', '%Telekomunikasi%')
                  ->first();

if($prodiTTEL) {
    $prodiTTEL->kurikulum_data = $kurikulumTTEL;
    $prodiTTEL->total_sks = array_sum(array_column($kurikulumTTEL, 'sks'));
    $prodiTTEL->jumlah_semester = 8;
    $prodiTTEL->save();
    
    echo "✅ Kurikulum berhasil ditambahkan ke prodi: {$prodiTTEL->nama_prodi}\n";
    echo "📚 Total: " . count($kurikulumTTEL) . " mata kuliah\n";
    echo "🎓 Total SKS: {$prodiTTEL->total_sks}\n";
} else {
    echo "❌ Prodi Teknik Telekomunikasi tidak ditemukan\n";
    echo "📋 Daftar prodi yang tersedia:\n";
    $allProdis = Prodi::all();
    foreach($allProdis as $p) {
        echo "   - {$p->nama_prodi}\n";
    }
}
