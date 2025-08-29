<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\SPP;
use App\Models\Modul;
use App\Models\Order;
use App\Models\Notif;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Additional;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index(Request $request)
    {
		//dd($request->all());
        $months = array(
            '1' => 'Jan',
            '2' => 'Feb',
            '3' => 'Mar',
            '4' => 'Apr',
            '5' => 'May',
            '6' => 'Jun',
            '7' => 'Jul',
            '8' => 'Aug',
            '9' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        );
        
        
        $year = isset($request->tahun) ? $request->tahun : Carbon::now()->format('Y');
        $month = isset($request->month) ? $request->month : Carbon::now()->format('m');
        
        //$siswa = auth()->user()->siswaView();
        
        $filterDate = Carbon::createFromDate($year, $month+1, 1);
        
        $siswaQuery = auth()->user()->siswaView();
		//dd($siswaQuery[0]);
		// Search query
    if ($request->has('search')) {
    $searchTerm = $request->search;

    $siswaQuery = $siswaQuery->filter(function ($item) use ($searchTerm) {
    // Perform case-insensitive search on 'nama' attribute
    return stripos($item->nama, $searchTerm) !== false;
});
    

    // Uncomment the following line if you want to see the generated SQL query for debugging
    
}


// You can apply filtering before fetching the results
$siswaQuery = $siswaQuery->filter(function ($item) use ($filterDate, $year, $month) {
    $tanggalMasuk = Carbon::parse($item->tanggal_masuk);
    $tanggalKeluar = $item->tanggal_lulus != null ? Carbon::parse($item->tanggal_lulus)->addMonths('1') : Carbon::createFromDate($year+100, $month+1, 1);
    
    return $tanggalMasuk->lte($filterDate) && $tanggalKeluar->gte($filterDate);
});

// Filter Kelas & Unit
		$reqUnit = $request->unit;
        $reqKelas = $request->kelas;
        
        $unit = auth()->user()->unitView();
		
		 if (isset($request->unit) && $request->unit != 'all') {
            $kelas = auth()->user()->kelasView()->where('unit_id', $request->unit);

            $siswaQuery = $siswaQuery->filter(function ($siswaItem) use ($kelas) {
                return $kelas->pluck('id')->contains($siswaItem->kelas_id);
            });
        } else {
            $kelas = auth()->user()->kelasView();
        }

        if (isset($request->kelas) && $request->kelas != 'all') {
            $siswaQuery = $siswaQuery->where('kelas_id', $request->kelas);
        } 
		
// Fetch the paginated results after filtering
$siswa = $siswaQuery->paginate(10)->appends($request->except('page'));
		$siswas = $siswaQuery;
        
        $orderCounts = Order::where('bulan', $month)
        ->where('tahun', $year)
        ->groupBy('modul_id')
        ->selectRaw('modul_id, COUNT(*) as count')
        ->with('modul') // Eager load the related modul
        ->get();
        
        $ordersByUnit = Order::with(['siswa.kelas.unit', 'modul'])
        ->where('bulan', $month)
        ->where('tahun', $year)
        ->get()
        ->groupBy(function ($order) {
            return $order->siswa->kelas->unit->nama; 
        })
      ->map(function ($orders) {
        return $orders->groupBy(function ($order) {
            return $order->siswa->kelas->nama;  
        })->map(function ($orders) {
            return $orders->groupBy(function ($order) {
                return $order->kategori; // Group by 'kategori'
            })->map(function ($orders) {
                return $orders/* ->filter(function ($order) {
                    return $order->kategori == 'baca' || $order->kategori == 'tulis' || $order->kategori == 'hitung'; // Filter orders where modul_id is not null
                }) */->groupBy('modul_id')->map(function ($orders) {
                    return $orders->count();
                });
            });
        });
    });
        
        //dd($ordersByUnit);
        $moduls = Modul::all();
        $sppAll = SPP::where('bulan', $month)->where('tahun', $year)->get();
        
        $aktif = $siswas->filter(function($e) use($month, $year) {
            $spp = $e->checkSPP($month, $year);
            return $spp && $spp->status == 'aktif';
        });
        
        $cuti = $siswas->filter(function($e) use($month, $year) {
            $spp = $e->checkSPP($month, $year);
            return $spp && $spp->status == 'cuti';
        });
        
        $keluar = $siswas->filter(function($e) use($month, $year) {
            $spp = $e->checkSPP($month, $year);
            return $spp && $spp->status == 'keluar';
        });
        
        $baru = $siswas->filter(function($e) use($month, $year) {
            // Siswa dianggap "baru" jika ditambahkan (created_at) di bulan dan tahun tersebut
            $createdAt = Carbon::parse($e->created_at);
            return $createdAt->month == $month && $createdAt->year == $year;
        });		$siswasi = $siswas->count();

        
        $orderAll = Order::where('bulan', $month)->where('tahun', $year)->get();
		$modulAll = Modul::where('status', 'Tersedia')->get();
		
		$orderCountsa = Order::groupBy('modul_id')
        ->selectRaw('modul_id, COUNT(*) as count')
        ->with('modul') // Eager load the related modul
        ->get();
		
        return view('order.index', compact('orderCountsa','siswasi', 'keluar', 'aktif', 'cuti', 'baru', 'months', 'year', 'month', 'siswa', 'orderCounts', 'ordersByUnit', 'moduls', 'unit', 'kelas', 'reqUnit', 'reqKelas', 'sppAll', 'orderAll', 'modulAll'));
    }
    
    /**
    * Show the form for creating a new resource.
    */
    public function create()
    {
        //
    }
    
    /**
    * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        //dd($request->all());
        $siswa_id = $request->siswa;
        
        $errorMessages = $this->checkLastLevel($siswa_id, $request->modul_baca, $request->modul_tulis, $request->modul_hitung, $request->modul_sd, $request->english, $request->iqro, $request->daftar, $request->lain, $request->verbal, $request->sempoa, $request->iq, $request->aritmatika, $request->juara, $request->ortu, $request->cryon);
        
        if (!empty($errorMessages)) {
            // Redirect back with error messages
            return redirect()->back()->with($errorMessages);
        }
        
        
        if ($request->modul_baca != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'baca'],
                ['modul_id' => $request->modul_baca, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'baca'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }
        
        if ($request->modul_tulis != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'tulis'],
                ['modul_id' => $request->modul_tulis, 'level' => $request->level, 'status' => $request->status]
            );
        }else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'tulis'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }
        
        if ($request->modul_hitung != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'hitung'],
                ['modul_id' => $request->modul_hitung, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'hitung'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }
        
        if ($request->modul_sd != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'modul SD'],
                ['modul_id' => $request->modul_sd, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'modul SD'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }
        
        if ($request->english != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'english'],
                ['modul_id' => $request->english, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'english'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }
        
        if ($request->iqro != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'iqro'],
                ['modul_id' => $request->iqro, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'iqro'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }

        if ($request->daftar != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'daftar'],
                ['modul_id' => $request->daftar, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'iqro'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }
        
        if ($request->lain != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'lain'],
                ['modul_id' => $request->lain, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'lain'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }

        if ($request->verbal != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'verbal'],
                ['modul_id' => $request->verbal, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'verbal'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }

        if ($request->sempoa != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'sempoa'],
                ['modul_id' => $request->sempoa, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'sempoa'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }

        if ($request->iq != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'iq'],
                ['modul_id' => $request->iq, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'iq'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }

        if ($request->aritmatika != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'aritmatika'],
                ['modul_id' => $request->aritmatika, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'aritmatika'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }

        if ($request->juara != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'juara'],
                ['modul_id' => $request->juara, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'juara'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }

        if ($request->ortu != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'ortu'],
                ['modul_id' => $request->ortu, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'ortu'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }

        if ($request->cryon != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'cryon'],
                ['modul_id' => $request->cryon, 'level' => $request->level, 'status' => $request->status]
            );
        } else {
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'cryon'],
                ['modul_id' => null, 'level' => $request->level, 'status' => $request->status]
            );
        }
        
        
        return redirect('order')->with('status', 'Data Berhasil Disubmit');
    }
    
    /**
    * Display the specified resource.
    */
    public function show(string $id)
    {
        //
    }
    
    /**
    * Show the form for editing the specified resource.
    */
    public function edit(string $id, string $month, string $year)
    {
        //dd($id, $month, $year);
        $order = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->first();
        $baca = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'baca')->first();
        $tulis = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'tulis')->first();
        $hitung = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'hitung')->first();
        $modul_sd = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'modul SD')->first();
        $english = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'english')->first();
        $iqro = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'iqro')->first();
        $lain = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'lain')->first();
        $daftar = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'daftar')->first();
        $verbal = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'verbal')->first();
        $sempoa = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'sempoa')->first();
        $iq = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'iq')->first();
        $aritmatika = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'aritmatika')->first();
        $juara = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'juara')->first();
        $ortu = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'ortu')->first();
        $cryon = Order::where('siswa_id', $id)->where('bulan', $month)->where('tahun', $year)->where('kategori', 'cryon')->first();
		
		$modulAll = Modul::where('status', 'Tersedia')->get();
        
        return view('order.edit', compact('order', 'baca', 'tulis', 'hitung', 'modul_sd', 'english', 'iqro', 'lain', 'daftar', 'verbal', 'sempoa', 'iq', 'aritmatika', 'juara', 'ortu', 'cryon', 'modulAll'));
        
    }
    
    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, string $id)
    {
        //dd($request->all());
        $siswa_id = $request->siswa;
        
        $errorMessages = $this->checkLastLevel($siswa_id, $request->modul_baca, $request->modul_tulis, $request->modul_hitung, $request->modul_sd, $request->english, $request->iqro, $request->daftar, $request->lain, $request->verbal, $request->sempoa, $request->iq, $request->aritmatika, $request->juara, $request->ortu, $request->cryon);
        
        if (!empty($errorMessages)) {
            // Redirect back with error messages
            return redirect()->back()->with($errorMessages);
        }
        
        
        if ($request->modul_baca != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'baca'],
                ['modul_id' => $request->modul_baca, 'level' => $request->level, 'status' => $request->status]
            );
        } 
        
        if ($request->modul_tulis != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'tulis'],
                ['modul_id' => $request->modul_tulis, 'level' => $request->level, 'status' => $request->status]
            );
        }
        
        if ($request->modul_hitung != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'hitung'],
                ['modul_id' => $request->modul_hitung, 'level' => $request->level, 'status' => $request->status]
            );
        } 
        
        if ($request->modul_sd != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'modul SD'],
                ['modul_id' => $request->modul_sd, 'level' => $request->level, 'status' => $request->status]
            );
        } 
        
        if ($request->english != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'english'],
                ['modul_id' => $request->english, 'level' => $request->level, 'status' => $request->status]
            );
        } 
        
        if ($request->iqro != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'iqro'],
                ['modul_id' => $request->iqro, 'level' => $request->level, 'status' => $request->status]
            );
        } 

        if ($request->daftar != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'daftar'],
                ['modul_id' => $request->daftar, 'level' => $request->level, 'status' => $request->status]
            );
        } 
        
        if ($request->lain != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'lain'],
                ['modul_id' => $request->lain, 'level' => $request->level, 'status' => $request->status]
            );
        } 

        if ($request->verbal != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'verbal'],
                ['modul_id' => $request->verbal, 'level' => $request->level, 'status' => $request->status]
            );
        } 

        if ($request->sempoa != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'sempoa'],
                ['modul_id' => $request->sempoa, 'level' => $request->level, 'status' => $request->status]
            );
        } 
        if ($request->iq != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'iq'],
                ['modul_id' => $request->iq, 'level' => $request->level, 'status' => $request->status]
            );
        } 

        if ($request->aritmatika != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'aritmatika'],
                ['modul_id' => $request->aritmatika, 'level' => $request->level, 'status' => $request->status]
            );
        } 

        if ($request->juara != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'juara'],
                ['modul_id' => $request->juara, 'level' => $request->level, 'status' => $request->status]
            );
        } 

        if ($request->ortu != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'ortu'],
                ['modul_id' => $request->ortu, 'level' => $request->level, 'status' => $request->status]
            );
        } 

        if ($request->cryon != null) {
            
            $order = Order::updateOrCreate(
                ['siswa_id' => $request->siswa, 'bulan' => $request->month, 'tahun' => $request->tahun, 'kategori' => 'cryon'],
                ['modul_id' => $request->cryon, 'level' => $request->level, 'status' => $request->status]
            );
        } 
        
        
        return redirect('order')->with('status', 'Data Berhasil Disubmit');
    }
    
    /**
    * Remove the specified resource from storage.
    */
    public function destroy(string $id)
    {
        //
    }
    
    public function checkLastLevel($siswa_id, $baca = null, $tulis = null, $hitung = null, $evaluasi = null, $english = null, $iqro = null, $daftar =null, $lain = null, $verbal = null, $sempoa = null, $iq = null, $aritmatika = null, $juara = null, $ortu = null, $cryon = null)
    {
        $siswa = Siswa::findOrFail($siswa_id);
        $errorMessages = [];
        
        if ($baca !== null) {
            $modul = Modul::findorfail($baca);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'baca')
                ->first();
                
                $siswa_order = $siswa->order;
                //dd($siswa_order);
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-baca-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
            }
        }
        
        if ($tulis !== null) {
            $modul = Modul::findorfail($tulis);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'tulis')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-tulis-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
            }
        }
        
        if ($hitung !== null) {
            $modul = Modul::findorfail($hitung);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'hitung')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-hitung-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
            }
        }
        
        if ($evaluasi !== null) {
            $modul = Modul::findorfail($evaluasi);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'modul SD')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-sd-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
                
            }
        }
        
        if ($english !== null) {
            $modul = Modul::findorfail($english);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'english')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-english-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
                
            }
        }
        
        if ($iqro !== null) {
            $modul = Modul::findorfail($iqro);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'iqro')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-iqro-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
                
            }
        }

        if ($daftar !== null) {
            $modul = Modul::findorfail($daftar);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'daftar')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-daftar-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
                
            }
        }
        
        if ($lain !== null) {
            $modul = Modul::findorfail($lain);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'lain')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-lain-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
                
            }
        }

        if ($verbal !== null) {
            $modul = Modul::findorfail($verbal);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'verbal')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-verbal-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
                
            }
        }

        if ($sempoa !== null) {
            $modul = Modul::findorfail($sempoa);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'sempoa')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-sempoa-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
                
            }
        }

        if ($iq !== null) {
            $modul = Modul::findorfail($iq);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'iq')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-iq-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
                
            }
        }

        if ($aritmatika !== null) {
            $modul = Modul::findorfail($aritmatika);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'aritmatika')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-aritmatika-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
                
            }
        }

        if ($juara !== null) {
            $modul = Modul::findorfail($juara);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'juara')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-juara-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
                
            }
        }

        if ($ortu !== null) {
            $modul = Modul::findorfail($ortu);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'ortu')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-ortu-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
                
            }
        }

        if ($cryon !== null) {
            $modul = Modul::findorfail($cryon);
            
            if ($siswa->order !== null) {
                $latestOrder = $siswa->order
                ->sortByDesc(function ($order) {
                    return Carbon::createFromDate($order->year, $order->month, 1);
                })
                ->where('kategori', 'cryon')
                ->first();
                
                $siswa_order = $siswa->order;
                
                $modulCount = $siswa_order->where('modul_id', $modul->id)->count();
                if ($latestOrder !== null && $modulCount >= 2) {
                    $errorMessages['error-cryon-'.$siswa_id] = 'Modul sudah diambil maksimal 2 kali.';
                }
                
            }
        }
        
        
        return $errorMessages;
    }
    
    
    public function konfirmasiSPP(Request $request)
    {
        $spp = SPP::findorfail($request->id);
        $spp->verified = $request->status;
        $spp->save();
		
		$spp->notif()->delete();

        
        return redirect('order')->with('status', 'Verifikasi Pembayaran Berhasil');
    }

    public function inputSPP(Request $request)
    {
		$sp = SPP::where('siswa_id', $request->id)->where('bulan', $request->month)->where('tahun', $request->tahun)->first();
		
		
		
        $spp = SPP::updateOrCreate(
            ['siswa_id' => $request->id, 'bulan' => $request->month, 'tahun' => $request->tahun],
            ['tanggal' => $request->tanggal, 'status' => $request->status]
        );
        
        if ($request->status == 'keluar') {
            $siswa = Siswa::findorfail($request->id);
            $siswa->status = 'keluar';
            $siswa->tanggal_lulus = Carbon::now();
            $siswa->save();
        } 

		$admin1 = User::where('role', 'administrator 1')->get();
		
		foreach($admin1 as $ad){
        if(isset($sp)){
			$not = new Notif;
			$not->user_id = $ad->id;
			$not->spp_id = $spp->id;
			$not->status = 1;
			$not->type = 'update';
			$not->save();
		} else {
		$not = new Notif;
			$not->user_id = $ad->id;
			$not->spp_id = $spp->id;
			$not->status = 1;
			$not->type = 'baru';
			$not->save();
		}
		}
        return redirect('order')->with('status', 'Input Pembayaran Berhasil');
    }

    public function insertAdditional(Request $request)
    {
        $pay = Additional::updateOrCreate(
            ['siswa_id' => $request->id, 'bulan' => $request->month, 'tahun' => $request->tahun],
            ['biaya' => $request->biaya, 'status' => $request->status]
        );

        return redirect('order')->with('status', 'Input Pembayaran Berhasil');
    }

    public function exportOrder($month, $year) 
    {
        return Excel::download(new OrderExport($month, $year), 'Data Order.xlsx');
    }
	
	public function searchItem(Request $request)
	{
	
	}
    
}
