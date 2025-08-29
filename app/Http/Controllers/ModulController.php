<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\Order;
use App\Exports\ModulExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ModulController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modul = Modul::all();
		$orderCountsa = Order::groupBy('modul_id')
        ->selectRaw('modul_id, COUNT(*) as count')
        ->with('modul') // Eager load the related modul
        ->get();
        
        return view('modul.index', compact('modul', 'orderCountsa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modul.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $modul = new Modul;
        $modul->nama = $request->nama;
        $modul->kategori = $request->kategori;
        $modul->level = $request->level;
        $modul->save();

        return redirect('modul')->with('status', 'Modul Berhasil Ditambahkan');
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
        $modul = Modul::findorfail($id);

        return view('modul.edit', compact('modul'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $modul = Modul::findorfail($id);
        $modul->nama = $request->nama;
        $modul->kategori = $request->kategori;
        $modul->level = $request->level;
        $modul->save();

        return redirect('modul')->with('status', 'Modul Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $modul = Modul::findorfail($id);
        $order = $modul->order;

        if (count($order) > 0) {
            return redirect()->back()->with('error', 'Tidak Bisa Menghapus Modul, Modul Memiliki Order Aktif');
        } else {
            Modul::destroy($id);

            return redirect()->back()->with('status', 'Modul Berhasil dihapus');
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $modul = Modul::findorfail($id);
        $modul->status = $request->status;
        $modul->save();

        return $modul;
    }

    public function tambahStock(Request $request, $id)
    {
        $modul = Modul::findorfail($id);
        $modul->stock += $request->stock;
        $modul->save();

        return redirect()->back()->with('status', 'Stock Berhasil Ditambahkan');
    }

    public function kurangStock(Request $request, $id)
    {
        $modul = Modul::findorfail($id);
        $modul->stock -= $request->stock;
        $modul->save();

        return redirect()->back()->with('status', 'Stock Berhasil Dikurangi');
    }

    public function exportModul() 
    {
        return Excel::download(new ModulExport, 'Data Modul.xlsx');
    }
}
