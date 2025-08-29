<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\SPP;
use App\Models\Absen;
use App\Models\Kelas;
use App\Models\Order;
use App\Models\AbsenNotes;
use App\Models\Additional;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'nim' ,
        'nama',
        'tanggal_lahir',
        'tempat_lahir' ,
        'tanggal_masuk' ,
        'nama_ayah',
        'nama_ibu',
        'status',
        'tanggal_pembayaran',
        'no_wali_1'
    ];
    
    protected $dates = [
        'tanggal_masuk'
    ];
    
    /**
    * Get the kelas that owns the Siswa
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
    
    /**
    * Get all of the order for the Siswa
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    
    /**
    * Get all of the absen for the Siswa
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function absen(): HasMany
    {
        return $this->hasMany(Absen::class);
    }
    
    /**
    * Get all of the spp for the Siswa
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function spp(): HasMany
    {
        return $this->hasMany(SPP::class);
    }

    /**
     * Get all of the additional for the Siswa
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function additional(): HasMany
    {
        return $this->hasMany(Additional::class);
    }

    /**
     * Get all of the absenNotes for the Siswa
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function absenNotes(): HasMany
    {
        return $this->hasMany(AbsenNotes::class);
    }
    
    public function chooseModul($kategori, $modulAll)
    {
        if ($this->level != null) {
            $modul = $modulAll->where('level',  $this->level)->where('status', 'Tersedia')->where('kategori', $kategori);
        } else {
            $modul = array();
        }
        return $modul;
    }
	
	public function chooseModulAlt($kategori)
    {
		$lastorder = $this->order->where('kategori', $kategori);
		$maxLevel = 0;
		
		foreach ($lastorder as $order) {
        $modul = $order->modul;

        // If $maxLevel is null or the current modul's level is greater than $maxLevel, update $maxLevel
        if ($modul &&  $modul->level > $maxLevel) {
            $maxLevel = $modul->level;
        }
    }
		
        if ($maxLevel != 0) {
            $modul = Modul::where('level', '>=', $maxLevel)->where('status', 'Tersedia')->where('kategori', $kategori)->get();
        } else {
            $modul = Modul::where('kategori', $kategori)->where('status', 'Tersedia')->get();
        }
        return $modul;
    }
	
	public function chooseModulAll($kategori)
    {
        $modul = Modul::where('kategori', $kategori)->where('status', 'Tersedia')->get();
        
        return $modul;
    }
    
    
    public function checkStatus($status = null)
    {
        if ($status === null) {
            $stat = $this->status;
        } else {
            $stat = $status;
        }
        
        if ($stat == 'aktif') {
            return 'siswa-status-aktif';
        } elseif ($stat == 'cuti') {
            return 'siswa-status-cuti';
        } elseif ($stat == 'keluar') {
            return 'siswa-status-keluar';
        } elseif ($stat == 'lulus') {
            return 'siswa-status-lulus';
        }
        
    }
    
    public function checkOrder($month, $tahun)
    {
        $order = $this->order->where('bulan', $month)->where('tahun', $tahun)->first();
        
        return $order;
    }
    
    public function checkSPP($month, $tahun)
    {
        $spp = $this->spp->where('bulan', $month)->where('tahun', $tahun)->first();
        
        return $spp;
    }

    public function checkAbsenNotes($month, $tahun)
    {
        $note = $this->absenNotes->where('bulan', $month)->where('tahun', $tahun)->first();
        
        return $note;
    }
    
    public function checkOrderSpec($month, $tahun, $kategori)
    {
        $order = $this->order->where('bulan', $month)->where('tahun', $tahun)->where('kategori', $kategori)->first();
        
        return $order;
    }
    
    
    public function latestModul($kategori)
    {
        $modul = null;
        
        if ($this->order !== null) {
            $latestOrder = $this->order
            ->sortByDesc(function ($order) {
                return Carbon::createFromDate($order->year, $order->month, 1);
            })
            ->where('kategori', $kategori)->whereNotNull('modul_id')
            ->first();
            
            if ($latestOrder !== null) {
                $modul = $latestOrder->modul;
            }
        }
        
        return $modul;
    }
    
    public function countAbsen($month, $year)
    {
        $absenCount = $this->absen;
        $count = 0;
        foreach ($absenCount as $key => $value) {
            $tanggal = Carbon::parse($value->tanggal_absen);

            if ($tanggal->month == $month && $tanggal->year == $year && $value->status == 'masuk') {
                $count += 1;
            }
        }

        
        return $count;
    }
    
    
}
