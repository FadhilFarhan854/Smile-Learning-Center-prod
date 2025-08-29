<?php

namespace App\Models;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AbsenNotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'bulan',
        'tahun',
        'keterangan'
    ];

    /**
     * Get the siswa that owns the AbsenNotes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
