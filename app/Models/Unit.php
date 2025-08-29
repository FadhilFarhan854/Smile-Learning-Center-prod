<?php

namespace App\Models;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    /**
     * Get all of the kelas for the Unit
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    public function jumlahKelas()
    {
        $kelas = $this->kelas->count();

        return $kelas;
    }
}
