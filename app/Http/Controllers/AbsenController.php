<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Absen;
use App\Models\AbsenNotes;
use App\Exports\AbsenExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AbsenController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index(Request $request)
    {
        // Cache static data
        $months = Cache::remember('months_array', 3600, function () {
            return [
                '1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr',
                '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug',
                '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec',
            ];
        });
        
        $weekMap = Cache::remember('week_map', 3600, function () {
            return [
                0 => 'Min', 1 => 'Sen', 2 => 'Sel', 3 => 'Rab',
                4 => 'Kam', 5 => 'Jum', 6 => 'Sab',
            ];
        });
        
        $year = $request->tahun ?? Carbon::now()->format('Y');
        $month = $request->month ?? Carbon::now()->format('m');
        $filterDate = Carbon::createFromDate($year, $month+1, 1);
        $basicDate = Carbon::createFromDate($year, $month, 1);
        
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        
        $reqUnit = $request->unit;
        $reqKelas = $request->kelas;
        
        $unit = auth()->user()->unitView();
        
        // Optimized query - use database filtering instead of collection filtering
        $siswaQuery = $this->getSiswaQuery($request, $year, $month, $filterDate);
        
        if (isset($request->unit) && $request->unit != 'all') {
            $kelas = auth()->user()->kelasView()->where('unit_id', $request->unit);
            $kelasIds = $kelas->pluck('id')->toArray();
            $siswaQuery = $siswaQuery->whereIn('kelas_id', $kelasIds);
        } else {
            $kelas = auth()->user()->kelasView();
        }

        if (isset($request->kelas) && $request->kelas != 'all') {
            $siswaQuery = $siswaQuery->where('kelas_id', $request->kelas);
        } 

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $siswaQuery = $siswaQuery->where('nama', 'LIKE', '%' . $searchTerm . '%');
        }
		
		$siswa = $siswaQuery->paginate(15)->appends($request->except('page'));
        
        // Get all siswa IDs for batch querying
        $siswaIds = $siswa->pluck('id')->toArray();
        
        // Early return if no siswa found
        if (empty($siswaIds)) {
            return view('absen.index', compact('months', 'year', 'month', 'daysInMonth', 'weekMap', 'siswa', 'kelas', 'unit', 'reqUnit', 'reqKelas'))
                ->with('dateToFill', []);
        }
        
        // Get all absen data for the month in one optimized query
        $startDate = Carbon::createFromDate($year, $month, 1)->format('Y-m-d');
        $endDate = Carbon::createFromDate($year, $month, $daysInMonth)->format('Y-m-d');
        
        $absenData = Absen::select('siswa_id', 'tanggal_absen', 'status', 'pertemuan')
            ->whereIn('siswa_id', $siswaIds)
            ->whereBetween('tanggal_absen', [$startDate, $endDate])
            ->get()
            ->groupBy('siswa_id')
            ->map(function ($absenList) {
                return $absenList->keyBy(function ($absen) {
                    return Carbon::parse($absen->tanggal_absen)->day;
                });
            });
        
        $dateToFill = $this->buildDateToFillArray($siswa, $absenData, $basicDate, $year, $month);
        
        
        //dd($dateToFill);
        return view('absen.index', compact('months', 'year', 'month', 'daysInMonth', 'weekMap', 'siswa', 'dateToFill', 'kelas', 'unit', 'reqUnit', 'reqKelas'));
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
        try {
            // Log untuk debugging
            Log::info('Absen store method called', [
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            // Validate the request
            $validator = Validator::make($request->all(), [
                'siswa_id' => 'required|integer|exists:siswas,id',
                'year' => 'required|numeric|min:2020|max:2030',
                'month' => 'required|numeric|min:1|max:12',
                'date' => 'required|numeric|min:1|max:31',
                'status' => 'required|string',
                'pertemuan' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', $validator->errors()->toArray());
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Convert input to integers untuk memastikan format yang benar
            $year = (int) $request->year;
            $month = (int) $request->month;
            $date = (int) $request->date;
            
            $tanggal = Carbon::createFromDate($year, $month, $date)->format('Y-m-d');
            
            Log::info('Attempting to save absen', [
                'siswa_id' => $request->siswa_id,
                'tanggal_absen' => $tanggal,
                'status' => $request->status,
                'pertemuan' => $request->pertemuan,
                'original_inputs' => [
                    'year' => $request->year,
                    'month' => $request->month, 
                    'date' => $request->date
                ],
                'converted_inputs' => [
                    'year' => $year,
                    'month' => $month,
                    'date' => $date
                ]
            ]);

            // Use updateOrCreate for better performance and cleaner code
            $absen = Absen::updateOrCreate(
                [
                    'siswa_id' => $request->siswa_id,
                    'tanggal_absen' => $tanggal
                ],
                [
                    'status' => $request->status,
                    'pertemuan' => $request->pertemuan
                ]
            );

            Log::info('Absen saved successfully', ['absen_id' => $absen->id]);
            
            return redirect()->back()->with('success', 'Data absen berhasil disimpan');

        } catch (\Exception $e) {
            Log::error('Error in store method', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Gagal menyimpan data absen: ' . $e->getMessage());
        }
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
    public function edit(string $id)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, string $id)
    {
        //
    }
    
    /**
    * Remove the specified resource from storage.
    */
    public function destroy(string $id)
    {
        //
    }

    public function inputNote(Request $request)
    {
        try {
            // Log untuk debugging
            Log::info('AbsenNote inputNote method called', [
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            // Add validation for better security and performance
            $validator = Validator::make($request->all(), [
                'siswa_id' => 'required|integer|exists:siswas,id',
                'month' => 'required|numeric|min:1|max:12',
                'year' => 'required|numeric|min:2020|max:2100',
                'keterangan' => 'required|string|max:1000'
            ]);

            if ($validator->fails()) {
                Log::error('AbsenNote validation failed', $validator->errors()->toArray());
                return redirect()->back()->withErrors($validator)->withInput();
            }

            AbsenNotes::updateOrCreate(
                [
                    'siswa_id' => $request->siswa_id, 
                    'bulan' => (int) $request->month, 
                    'tahun' => (int) $request->year
                ],
                ['keterangan' => $request->keterangan]
            );

            Log::info('AbsenNote saved successfully');
            return redirect('absen')->with('success', 'Input Note Berhasil');

        } catch (\Exception $e) {
            Log::error('Error in AbsenNote inputNote method', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()->with('error', 'Gagal menyimpan note: ' . $e->getMessage());
        }
    }

    public function deleteNote(Request $request)
    {
        try {
            // Log untuk debugging
            Log::info('AbsenNote deleteNote method called', [
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            // Add validation and error handling
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:absen_notes,id'
            ]);

            if ($validator->fails()) {
                Log::error('AbsenNote delete validation failed', $validator->errors()->toArray());
                return redirect()->back()->withErrors($validator);
            }

            AbsenNotes::destroy($request->id);
            
            Log::info('AbsenNote deleted successfully', ['note_id' => $request->id]);
            return redirect('absen')->with('success', 'Hapus Note Berhasil');

        } catch (\Exception $e) {
            Log::error('Error in AbsenNote deleteNote method', [
                'note_id' => $request->id ?? 'unknown',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect('absen')->with('error', 'Gagal menghapus note: ' . $e->getMessage());
        }
    }

    public function exportAbsen($month, $year) 
    {
        // Add basic validation
        if (!is_numeric($month) || !is_numeric($year) || $month < 1 || $month > 12) {
            return redirect()->back()->with('error', 'Parameter bulan atau tahun tidak valid');
        }

        try {
            return Excel::download(new AbsenExport($month, $year), "Data_Absen_{$month}_{$year}.xlsx");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengexport data absen');
        }
    }

    /**
     * Get optimized siswa query based on user role and date filters
     */
    private function getSiswaQuery(Request $request, $year, $month, $filterDate)
    {
        $user = auth()->user();
        
        // Start with base siswa query based on user role
        if ($user->role === 'administrator 1' || $user->role == 'administrator 2') {
            $query = \App\Models\Siswa::with('spp');
        } elseif ($user->role == 'admin') {
            $kelasIds = $user->kelas->pluck('id')->toArray();
            $query = \App\Models\Siswa::with('spp')->whereIn('kelas_id', $kelasIds);
        } else {
            $kelasIds = $user->kelasAjar->pluck('id')->toArray();
            $query = \App\Models\Siswa::with('spp')->whereIn('kelas_id', $kelasIds);
        }
        
        // Apply date filters at database level
        $query->where(function ($q) use ($filterDate, $year, $month) {
            $q->where('tanggal_masuk', '<=', $filterDate)
              ->where(function ($subQ) use ($year, $month) {
                  $subQ->whereNull('tanggal_lulus')
                       ->orWhere('tanggal_lulus', '>=', Carbon::createFromDate($year, $month, 1));
              });
        });
        
        return $query->orderBy('kelas_id');
    }

    /**
     * Build the dateToFill array efficiently
     */
    private function buildDateToFillArray($siswa, $absenData, $basicDate, $year, $month)
    {
        $dateToFill = [];
        $endOfMonth = $basicDate->endOfMonth()->day;
        
        foreach ($siswa as $siswaItem) {
            $siswaId = $siswaItem->id;
            $itemDate = Carbon::parse($siswaItem->tanggal_masuk);
            $lulusDate = $siswaItem->tanggal_lulus ? Carbon::parse($siswaItem->tanggal_lulus) : null;
            
            // Initialize all days as EMPTY first
            for ($day = 1; $day <= $endOfMonth; $day++) {
                $dateToFill[$siswaId][$day] = ['status' => 'EMPTY', 'pertemuan' => null];
            }
            
            // Handle PRE status (before joining)
            if ($itemDate->isSameMonth($basicDate) && $itemDate->isSameYear($basicDate)) {
                for ($day = 1; $day < $itemDate->day; $day++) {
                    $dateToFill[$siswaId][$day]['status'] = 'PRE';
                }
            }
            
            // Handle POST status (after graduation)
            if ($lulusDate && $lulusDate->isSameMonth($basicDate) && $lulusDate->isSameYear($basicDate)) {
                for ($day = $lulusDate->day + 1; $day <= $endOfMonth; $day++) {
                    $dateToFill[$siswaId][$day]['status'] = 'POST';
                }
            }
            
            // Fill actual absen data
            if (isset($absenData[$siswaId])) {
                foreach ($absenData[$siswaId] as $day => $absen) {
                    $dateToFill[$siswaId][$day]['status'] = $absen->status;
                    $dateToFill[$siswaId][$day]['pertemuan'] = $absen->pertemuan;
                }
            }
        }
        
        return $dateToFill;
    }
}
