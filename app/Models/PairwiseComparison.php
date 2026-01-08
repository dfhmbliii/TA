<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PairwiseComparison extends Model
{
    use HasFactory;

    protected $fillable = [
        'kriteria_1_id',
        'kriteria_2_id',
        'nilai',
    ];

    protected $casts = [
        'nilai' => 'decimal:6',
    ];

    public function kriteria1()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_1_id');
    }

    public function kriteria2()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_2_id');
    }
}
