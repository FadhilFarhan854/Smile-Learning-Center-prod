<?php

namespace App\Models;

use App\Models\Siswa;
use App\Models\Notif;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SPP extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'bulan',
        'tahun',
        'status',
        'tanggal'
    ];

    /**
     * Get the siswa that owns the SPP
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
	
	/**
    * Get all of the kelas for the User
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function notif(): HasMany
    {
        return $this->hasMany(Notif::class, 'spp_id');
    }
}
