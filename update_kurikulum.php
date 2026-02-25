<?php
// Script untuk update kurikulum data ke database
// Jalankan dengan: php update_kurikulum.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Prodi;

// Kurikulum lengkap untuk Sistem Informasi berdasarkan data terbaru
$kurikulumSI = [
    // Semester 1 - Total 19 SKS
    ['semester' => 1, 'kode' => 'SI101', 'nama' => 'Agama', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'SI102', 'nama' => 'Internalisasi Budaya dan Pembentukan Karakter', 'sks' => 1, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'SI103', 'nama' => 'Algoritma dan Pemrograman', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'SI104', 'nama' => 'Matematika Diskrit', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'SI105', 'nama' => 'Matematika untuk Sistem Informasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'SI106', 'nama' => 'Pengantar Sistem Informasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 1, 'kode' => 'SI107', 'nama' => 'Sistem Enterprise', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 2 - Total 18 SKS
    ['semester' => 2, 'kode' => 'SI201', 'nama' => 'Design Thinking', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'SI202', 'nama' => 'Jaringan Komputer', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'SI203', 'nama' => 'Kepemimpinan dan Komunikasi Interpersonal', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'SI204', 'nama' => 'Manjemen Rantai Pasok', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'SI205', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SI103'],
    ['semester' => 2, 'kode' => 'SI206', 'nama' => 'Probabilitas dan Statistik', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 2, 'kode' => 'SI207', 'nama' => 'Sistem Basis Data', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 3 - Total 20 SKS
    ['semester' => 3, 'kode' => 'SI301', 'nama' => 'Analisis dan Perancangan Sistem Informasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SI106'],
    ['semester' => 3, 'kode' => 'SI302', 'nama' => 'Etika Profesi, Regulasi IT dan Properti Intelektual', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'SI303', 'nama' => 'Permodelan Bisnis', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'SI304', 'nama' => 'Pengembangan Aplikasi Website', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SI205'],
    ['semester' => 3, 'kode' => 'SI305', 'nama' => 'Perancangan Interaksi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'SI306', 'nama' => 'Sistem Operasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 3, 'kode' => 'SI307', 'nama' => 'Statistika Industri', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SI206'],
    
    // Semester 4 - Total 19 SKS
    ['semester' => 4, 'kode' => 'SI401', 'nama' => 'Integrasi Aplikasi Enterprise', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SI107'],
    ['semester' => 4, 'kode' => 'SI402', 'nama' => 'Keamanan Sistem Informasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'SI403', 'nama' => 'Manjemen Proyek Sistem', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SI301'],
    ['semester' => 4, 'kode' => 'SI404', 'nama' => 'Manjemen Proyek Sumber Daya Manusia', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 4, 'kode' => 'SI405', 'nama' => 'Data Mining', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SI207'],
    ['semester' => 4, 'kode' => 'SI406', 'nama' => 'Pengujian dan Implementasi Sistem', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => 'SI301'],
    ['semester' => 4, 'kode' => 'SI407', 'nama' => 'Rekayasa Proses Bisnis', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => 'SI303'],
    
    // Semester 5 - Total 20 SKS
    ['semester' => 5, 'kode' => 'SI501', 'nama' => 'Bahasa Inggris I', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'SI502', 'nama' => 'Arsitektur Enterprise', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SI401'],
    ['semester' => 5, 'kode' => 'SI503', 'nama' => 'Data Warehouse dan Business Intelligence', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SI405'],
    ['semester' => 5, 'kode' => 'SI504', 'nama' => 'KOmputasi Awan', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 5, 'kode' => 'SI505', 'nama' => 'Manajemen Data Enterprise', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SI207'],
    ['semester' => 5, 'kode' => 'SI506', 'nama' => 'Proyek Perangkat Lunak', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SI304'],
    ['semester' => 5, 'kode' => 'SI507', 'nama' => 'Sistem Informasi Akuntansi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    
    // Semester 6 - Total 19 SKS
    ['semester' => 6, 'kode' => 'SI601', 'nama' => 'Bahasa Indonesia', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'SI602', 'nama' => 'Bahasa Inggris II', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SI501'],
    ['semester' => 6, 'kode' => 'SI603', 'nama' => 'Kecerdasan Artifisial dan Penerapannya', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'SI604', 'nama' => 'Kerja Praktek dan Pengabdian Masyarakat', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'SI605', 'nama' => 'Tata Kelola Manajemen Teknologi Informasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => 'SI502'],
    ['semester' => 6, 'kode' => 'SI606', 'nama' => 'MK Pilihan Prodi I / MK MBKM / MK WRAP', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 6, 'kode' => 'SI607', 'nama' => 'MK Pilihan Prodi II / MK MBKM / MK WRAP', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    
    // Semester 7 - Total 17 SKS
    ['semester' => 7, 'kode' => 'SI701', 'nama' => 'Kewirausahaan', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'SI702', 'nama' => 'Capstone Project', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'SI506'],
    ['semester' => 7, 'kode' => 'SI703', 'nama' => 'Metode Penelitian dan Penyusunan Karya Ilmiah', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'SI704', 'nama' => 'MK Pilihan Prodi III / MK MBKM / MK WRAP', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'SI705', 'nama' => 'MK Pilihan Prodi IV / MK MBKM / MK WRAP', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    ['semester' => 7, 'kode' => 'SI706', 'nama' => 'MK Pilihan Prodi V / MK MBKM / MK WRAP', 'sks' => 3, 'kategori' => 'pilihan', 'prasyarat' => ''],
    
    // Semester 8 - Total 11 SKS
    ['semester' => 8, 'kode' => 'SI801', 'nama' => 'Kewarganegaraan', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 8, 'kode' => 'SI802', 'nama' => 'Pancasila', 'sks' => 2, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 8, 'kode' => 'SI803', 'nama' => 'Pelatihan dan Sertifikasi', 'sks' => 3, 'kategori' => 'wajib', 'prasyarat' => ''],
    ['semester' => 8, 'kode' => 'SI804', 'nama' => 'Tugas Akhir', 'sks' => 4, 'kategori' => 'wajib', 'prasyarat' => 'SI702'],
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
