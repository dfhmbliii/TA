<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KarirCategory extends Model
{
    use HasFactory;

    protected $table = 'karir_categories';

    protected $fillable = [
        'nama',
        'kode',
        'urutan',
        'bobot',
        'deskripsi',
    ];
}
