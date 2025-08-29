<?php

namespace App\Models;

use App\Models\Modul;
use App\Models\Siswa;
use App\Models\Notif;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'modul_id',
        'bulan',
        'tahun',
        'status',
        'level',
        'kategori'
    ];

    /**
     * Get the modul that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modul(): BelongsTo
    {
        return $this->belongsTo(Modul::class);
    }

    /**
     * Get the siswa that owns the Order
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
        return $this->hasMany(Notif::class);
    }

}
