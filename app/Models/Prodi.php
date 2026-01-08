<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $fillable = [
        'nama_prodi',
        'nama_fakultas',
        'kode_prodi',
        'deskripsi',
        'visi_misi',
        'prospek_kerja',
        'visi_misi_url',
        'prospek_url',
        'kurikulum_url',
        'kurikulum_embed',
        'kurikulum_data',
        'total_sks',
        'jumlah_semester',
    ];

    protected $casts = [
        'kurikulum_embed' => 'boolean',
        'kurikulum_data' => 'array',
    ];

    public function siswas()
    {
        return $this->hasMany(siswa::class);
    }

    public function alternatives()
    {
        return $this->hasMany(ProdiAlternative::class);
    }
}
