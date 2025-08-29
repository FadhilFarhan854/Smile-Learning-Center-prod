<?php

namespace App\Exports;

use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class UnitExport implements FromView
{
    public function view(): View
    {
        return view('exports.unit', [
            'unit' => auth()->user()->unitView()
        ]);
    }
}
