<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BakatCategory extends Model
{
    use HasFactory;

    protected $table = 'bakat_categories';

    protected $fillable = [
        'nama',
        'kode',
        'urutan',
        'bobot',
        'deskripsi',
    ];
}
