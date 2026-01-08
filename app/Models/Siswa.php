<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';

    protected $fillable = [
        'name',
        'nisn',
        'email',
        'prodi_id',
        'jurusan_sma',
        'asal_sekolah',
        'tahun_lulus',
        'ipk',
        'prestasi_score',
        'kepemimpinan',
        'sosial',
        'komunikasi',
        'kreativitas',
        'spk_last_score',
        'spk_last_category',
    ];

    protected $casts = [
        'ipk' => 'float',
        'prestasi_score' => 'float',
        'kepemimpinan' => 'float',
        'sosial' => 'float',
        'komunikasi' => 'float',
        'kreativitas' => 'float',
        'spk_last_score' => 'float',
    ];

    public function spkResults()
    {
        return $this->hasMany(SpkResult::class, 'siswa_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
