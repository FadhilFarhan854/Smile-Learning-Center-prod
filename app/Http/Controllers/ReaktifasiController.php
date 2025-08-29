<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class ReaktifasiController extends Controller
{
    /**
     * Display a listing of students with 'keluar' status.
     */
    public function index(Request $request)
    {
        $siswaQuery = auth()->user()->siswaView()->where('status', 'keluar');
        
        // Search query
        if ($request->has('search')) {
            $searchTerm = $request->search;

            $siswaQuery = $siswaQuery->filter(function ($item) use ($searchTerm) {
                // Perform case-insensitive search on 'nama' attribute
                return stripos($item->nama, $searchTerm) !== false;
            });
        }

        // Filter Unit & Kelas
        $reqUnit = $request->unit;
        $reqKelas = $request->kelas;
        
        $unit = auth()->user()->unitView();
        
        if (isset($request->unit) && $request->unit != 'all' && $request->unit != '') {
            $kelas = auth()->user()->kelasView()->where('unit_id', $request->unit);

            $siswaQuery = $siswaQuery->filter(function ($siswaItem) use ($kelas) {
                return $kelas->pluck('id')->contains($siswaItem->kelas_id);
            });
        } else {
            $kelas = auth()->user()->kelasView();
        }

        if (isset($request->kelas) && $request->kelas != 'all' && $request->kelas != '') {
            $siswaQuery = $siswaQuery->where('kelas_id', $request->kelas);
        }

        $siswa = $siswaQuery->paginate(10)->appends($request->except('page'));

        return view('reaktifasi.index', compact('siswa', 'unit', 'kelas', 'reqUnit', 'reqKelas'));
    }

    /**
     * Reactivate student by changing status from 'keluar' to 'aktif'
     */
    public function reaktifasi(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        
        // Check if student status is 'keluar'
        if ($siswa->status !== 'keluar') {
            return redirect()->back()->with('error', 'Siswa tidak dapat direaktifasi karena status bukan keluar');
        }
        
        // Change status to 'aktif'
        $siswa->status = 'aktif';
        $siswa->tanggal_lulus = null; // Clear graduation date
        $siswa->save();

        return redirect()->back()->with('status', 'Siswa berhasil direaktifasi');
    }
}
