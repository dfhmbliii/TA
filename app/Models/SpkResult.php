<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'weights',
        'criteria_values',
        'total_score',
        'category',
    ];

    protected $casts = [
        'weights' => 'array',
        'criteria_values' => 'array',
        'total_score' => 'float',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
