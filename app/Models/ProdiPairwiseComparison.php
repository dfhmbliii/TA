<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdiPairwiseComparison extends Model
{
    use HasFactory;

    protected $fillable = [
        'prodi_id',
        'alternative_1_id',
        'alternative_2_id',
        'nilai',
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function alternative1()
    {
        return $this->belongsTo(ProdiAlternative::class, 'alternative_1_id');
    }

    public function alternative2()
    {
        return $this->belongsTo(ProdiAlternative::class, 'alternative_2_id');
    }
}