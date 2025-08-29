<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class SiswaExport implements FromView
{
    public function view(): View
    {
        return view('exports.siswa', [
            'siswa' => auth()->user()->siswaView(),
            'user' => User::all()
        ]);
    }
}
