<?php

namespace App\Models;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Additional extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'bulan',
        'tahun',
        'status',
        'biaya'
    ];
    
    /**
     * Get the siswa that owns the Additional
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
