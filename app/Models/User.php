<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Unit;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Notif;
use App\Models\Siswa;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    
    /**
    * The attributes that should be hidden for serialization.
    *
    * @var array<int, string>
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
    * The attributes that should be cast.
    *
    * @var array<string, string>
    */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    /**
    * Get all of the kelas for the User
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }
	
	/**
    * Get all of the kelas for the User
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function notifa(): HasMany
    {
        return $this->hasMany(Notif::class);
    }

    /**
     * Get all of the kelasAjar for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kelasAjar(): HasMany
    {
        return $this->hasMany(Kelas::class, 'guru_id', 'id');
    }
    
    public function siswaView() 
    {
        if (auth()->user()->role === 'administrator 1' || auth()->user()->role == 'administrator 2') {
            return Siswa::with('spp')->get();
        } elseif (auth()->user()->role == 'admin') {
            $kelasIds = auth()->user()->kelas->pluck('id')->toArray();
            return Siswa::with('spp')->whereIn('kelas_id', $kelasIds)->orderBy('kelas_id')->get();
        } else { 
            $kelasIds = auth()->user()->kelasAjar->pluck('id')->toArray();
            return Siswa::with('spp')->whereIn('kelas_id', $kelasIds)->orderBy('kelas_id')->get();
        }
    }

    public function kelasView()
    {
        if (auth()->user()->role === 'administrator 1' || auth()->user()->role == 'administrator 2') {
            $kelas = Kelas::all();
        } elseif (auth()->user()->role == 'admin') {
            $kelas = auth()->user()->kelas;
        } else {
            $kelas = auth()->user()->kelasAjar;
        }

        return $kelas;
        
    }

    public function unitView()
    {
        $unit = collect();

        if (auth()->user()->role === 'administrator 1' || auth()->user()->role == 'administrator 2') {
            $unit = Unit::all();
        } elseif (auth()->user()->role == 'admin') {
            foreach (auth()->user()->kelas as $kelas) {
                $unit->push($kelas->unit);
            }
        } else {
            foreach (auth()->user()->kelasAjar as $kelas) {
                $unit->push($kelas->unit);
            }
        }
        //dd($unit);
        return $unit;
    }

    public function userView()
    {
        if (auth()->user()->role === 'administrator 1' || auth()->user()->role == 'administrator 2') {
            $user = User::where('status', 'aktif')->get();
        } elseif (auth()->user()->role == 'admin') {
            $user = User::where('id',auth()->user()->id)->get();
        } else {
            $user = User::where('id',auth()->user()->id)->get();
        }

        return $user;
    }
	
	public function notifAmount()
{
    return $this->notifa()->where('status', 1)->count();
}

	
	public function notifAll()
    {
        $notif = $this->notifa()->get()->sortByDesc(function ($notification) {
        return $notification->status;
    });

        return $notif;
    }
}
