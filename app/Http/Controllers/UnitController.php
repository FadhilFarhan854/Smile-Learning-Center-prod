<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Exports\UnitExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unit = auth()->user()->unitView();

        return view('unit.index', compact('unit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('unit.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $unit = new Unit;
        $unit->nama = $request->nama;
        $unit->save();

        return redirect('unit')->with('status', 'Data Berhasil Ditambahkan');
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
        $unit = Unit::findorfail($id);

        return view('unit.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $unit = Unit::findorfail($id);
        $unit->nama = $request->nama;
        $unit->save();

        return redirect('unit')->with('status', 'Data Berhasil Diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = Unit::findorfail($id);
        $kelas = $unit->kelas;

        if (count($kelas) > 0) {
            return redirect()->back()->with('error', 'Tidak Bisa Menghapus Unit, Unit Masih Memiliki Kelas Aktif');
        } else {
            Unit::destroy($id);

            return redirect()->back()->with('status', 'Unit Berhasil dihapus');
        }
    }

    public function exportUnit() 
    {
        return Excel::download(new UnitExport, 'Data Unit.xlsx');
    }
}
