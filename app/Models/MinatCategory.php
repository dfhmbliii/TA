<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinatCategory extends Model
{
    use HasFactory;

    protected $table = 'minat_categories';

    protected $fillable = [
        'nama',
        'kode',
        'urutan',
        'bobot',
        'deskripsi',
    ];
}
