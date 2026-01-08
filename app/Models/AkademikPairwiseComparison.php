<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkademikPairwiseComparison extends Model
{
    use HasFactory;

    protected $table = 'akademik_pairwise_comparisons';

    protected $fillable = [
        'category_1_id',
        'category_2_id',
        'nilai',
    ];

    protected $casts = [
        'nilai' => 'decimal:6',
    ];
}
