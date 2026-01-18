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
        'input_data',
        'rekomendasi_prodi',
        'criteria_breakdown'
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
    
    /**
     * Accessor untuk input_data - ensure always array
     */
    protected function inputData(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn ($value) => is_string($value) ? json_decode($value, true) ?? [] : ($value ?? []),
        );
    }
    
    /**
     * Accessor untuk rekomendasi_prodi - ensure always array
     */
    protected function rekomendasiProdi(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn ($value) => is_string($value) ? json_decode($value, true) ?? [] : ($value ?? []),
        );
    }
}
