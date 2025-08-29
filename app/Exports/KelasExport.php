<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class KelasExport implements FromView
{
    public function view(): View
    {
        return view('exports.kelas', [
            'kelas' => Kelas::all(),
            'admin' => User::where('role', 'admin')->where('status', 'aktif')->get(),
            'guru' => User::where('role', 'motivator')->where('status', 'aktif')->get()
        ]);
    }
}
