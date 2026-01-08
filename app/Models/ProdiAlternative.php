<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdiAlternative extends Model
{
    use HasFactory;

    protected $fillable = [
        'prodi_id',
        'nama_alternatif',
        'kode_alternatif',
        'deskripsi',
        'bobot',
        'urutan',
    ];

    protected $casts = [
        'bobot' => 'decimal:4',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}