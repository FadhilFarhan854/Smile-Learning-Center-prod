<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserExport implements FromView
{
    public function view(): View
    {
        return view('exports.user', [
            'user' => auth()->user()->userView()
        ]);
    }
}
