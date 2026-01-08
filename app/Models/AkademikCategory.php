<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkademikCategory extends Model
{
    use HasFactory;

    protected $table = 'akademik_categories';

    protected $fillable = [
        'nama',
        'kode',
        'urutan',
        'bobot',
        'deskripsi',
    ];
}
