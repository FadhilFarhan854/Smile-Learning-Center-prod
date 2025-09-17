<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\Order;
use App\Exports\ModulExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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
        try {
            // Log untuk debugging
            Log::info('Modul store method called', [
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            // Validate the request
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'kategori' => 'required|string|max:100',
                'level' => 'required|integer|min:1|max:10'
            ]);

            if ($validator->fails()) {
                Log::error('Modul validation failed', $validator->errors()->toArray());
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Log::info('Attempting to save modul', [
                'nama' => $request->nama,
                'kategori' => $request->kategori,
                'level' => $request->level
            ]);

            // Create new modul using mass assignment
            $modul = Modul::create([
                'nama' => $request->nama,
                'kategori' => $request->kategori,
                'level' => $request->level,
                'status' => 'tersedia', // Set default status
                'stock' => 0 // Set default stock
            ]);

            Log::info('Modul saved successfully', ['modul_id' => $modul->id]);

            return redirect('/modul/create')->with('status', 'Modul Berhasil Ditambahkan');

        } catch (\Exception $e) {
            Log::error('Error in modul store method', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Gagal menyimpan modul: ' . $e->getMessage());
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
        $modul = Modul::findorfail($id);

        return view('modul.edit', compact('modul'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Log untuk debugging
            Log::info('Modul update method called', [
                'modul_id' => $id,
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            // Validate the request
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'kategori' => 'required|string|max:100',
                'level' => 'required|integer|min:1|max:10'
            ]);

            if ($validator->fails()) {
                Log::error('Modul update validation failed', $validator->errors()->toArray());
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $modul = Modul::findOrFail($id);
            
            Log::info('Attempting to update modul', [
                'modul_id' => $id,
                'old_data' => $modul->toArray(),
                'new_data' => $request->only(['nama', 'kategori', 'level'])
            ]);

            // Update modul using mass assignment
            $modul->update([
                'nama' => $request->nama,
                'kategori' => $request->kategori,
                'level' => $request->level
            ]);

            Log::info('Modul updated successfully', ['modul_id' => $modul->id]);

            return redirect('modul')->with('status', 'Modul Berhasil Diubah');

        } catch (\Exception $e) {
            Log::error('Error in modul update method', [
                'modul_id' => $id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()->with('error', 'Gagal mengubah modul: ' . $e->getMessage());
        }
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
