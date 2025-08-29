<?php

namespace App\Exports;

use App\Models\Modul;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class ModulExport implements FromView
{
    public function view(): View
    {
        return view('exports.modul', [
            'modul' => Modul::all()
        ]);
    }
}
