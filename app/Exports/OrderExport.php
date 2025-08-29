<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Modul;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderExport implements FromView
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

        $siswa = auth()->user()->siswaView();
        
        $filterDate = Carbon::createFromDate($year, $month+1, 1);

        $siswa = $siswa->filter(function ($item) use ($filterDate, $year, $month) {
            $tanggalMasuk = Carbon::parse($item->tanggal_masuk);
            $tanggalKeluar = $item->tanggal_lulus != null ? Carbon::parse($item->tanggal_lulus)->addMonths('1') : Carbon::createFromDate($year+100, $month+1, 1);
            
            return $tanggalMasuk->lte($filterDate) && $tanggalKeluar->gte($filterDate);
        });

        $moduls = Modul::all();
        
        return view('exports.order', [
            'year' => $year, 
            'month' => $month,
            'siswa' => $siswa,
            'moduls' => $moduls
        ]);
    }
}
