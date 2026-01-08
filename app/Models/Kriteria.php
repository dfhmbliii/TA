<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'nama_kriteria',
        'kode_kriteria',
        'deskripsi',
        'bobot',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'bobot' => 'decimal:4',
        'is_active' => 'boolean',
    ];
}