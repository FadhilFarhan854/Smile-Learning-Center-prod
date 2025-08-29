<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Absen;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class AbsenExport implements FromView
{

    private $month;
    private $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View
    {
        $year = $this->year;
        $month = $this->month;

        $filterDate = Carbon::createFromDate($this->year, $this->month+1, 1);
        $basicDate = Carbon::createFromDate($this->year, $this->month, 1);

        $daysInMonth = Carbon::create($this->year, $this->month, 1)->daysInMonth;
        
        $weekMap = [
            0 => 'Min',
            1 => 'Sen',
            2 => 'Sel',
            3 => 'Rab',
            4 => 'Kam',
            5 => 'Jum',
            6 => 'Sab',
        ];

        $unit = auth()->user()->unitView();
        $kelas = auth()->user()->kelasView();

        $siswa = auth()->user()->siswaView()->filter(function ($item) use ($filterDate, $year, $month) {
            $tanggalMasuk = Carbon::parse($item->tanggal_masuk);
            $tanggalKeluar = $item->tanggal_lulus != null ? Carbon::parse($item->tanggal_lulus)->addMonths('1') : Carbon::createFromDate($year+100, $month+1, 1);
            
            return $tanggalMasuk->lte($filterDate) && $tanggalKeluar->gte($filterDate);
        });

        $dateToFill = [];
        
        foreach ($siswa as $key => $value) {
            $itemDate = Carbon::parse($value->tanggal_masuk);
            
            if ($value->tanggal_lulus) {
                $lulusDate = Carbon::parse($value->tanggal_lulus);
            } else {
                $lulusDate = null;
            }
            
            if ($itemDate->isSameMonth($basicDate) && $itemDate->isSameYear($basicDate)) {
                // Mark days before joining as 'PRE'
                for ($day = 1; $day < $itemDate->day; $day++) {
                    $dateToFill[$value->id][$day] = 'PRE';
                }
            } elseif ($lulusDate != null && $lulusDate->isSameMonth($basicDate) && $lulusDate->isSameYear($basicDate)) {
                // Mark days after graduating as 'POST'
                for ($day = $lulusDate->day + 1; $day <= $basicDate->endOfMonth()->day; $day++) {
                    $dateToFill[$value->id][$day] = 'POST';
                }
            }
            
            for ($day = 1; $day <= $basicDate->endOfMonth()->day; $day++) {
                if (!isset($dateToFill[$value->id][$day])) {
                    $dateToFill[$value->id][$day] = 'EMPTY';
                }
                $absen_date = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
                //dd($value->id);
                $status_absen = Absen::where('tanggal_absen', $absen_date)->where('siswa_id', $value->id)->first();
                //dd($status_absen);
                if (isset($status_absen)) {
                    $dateToFill[$value->id][$day] = $status_absen->status;
                }
            }
        }

        return view('exports.absen', [
            'year' => $year, 
            'month' => $month, 
            'daysInMonth' => $daysInMonth, 
            'weekMap' => $weekMap, 
            'siswa' => $siswa, 
            'dateToFill' => $dateToFill, 
            'kelas' => $kelas, 
            'unit' => $unit
        ]);
    }
}
