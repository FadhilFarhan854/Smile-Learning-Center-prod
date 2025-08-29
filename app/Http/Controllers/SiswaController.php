<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Siswa;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $siswaQuery = auth()->user()->siswaView();
        
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
        $user = User::all();

        return view('siswa.index', compact('siswa', 'user', 'unit', 'kelas', 'reqUnit', 'reqKelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = auth()->user()->kelasView();

        return view('siswa.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $siswa = new Siswa();
        $siswa->kelas_id = $request->kelas_id;
        $siswa->nim = str_pad($request->nim, 5, 0, STR_PAD_LEFT);
        $siswa->nama = $request->nama;
        $siswa->tanggal_masuk = $request->tanggal_masuk;
        $siswa->tanggal_lahir = $request->tanggal_lahir;
        $siswa->tanggal_pembayaran = $request->tanggal_pembayaran;
        $siswa->tempat_lahir = $request->tempat_lahir;
        $siswa->nama_ayah = $request->nama_ayah;
        $siswa->nama_ibu = $request->nama_ibu;
        $siswa->status = 'baru';
        $siswa->no_wali_1 = $request->no_wali_1;
        $siswa->no_wali_2 = $request->no_wali_2;
        $siswa->save();

        return redirect('siswa')->with('status', 'Data Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::findorfail($id);

        return view('siswa.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $siswa = Siswa::findorfail($id);
        $kelas = auth()->user()->kelasView();

        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findorfail($id);
        $siswa->kelas_id = $request->kelas_id;
        $siswa->nim = str_pad($request->nim, 5, 0, STR_PAD_LEFT);
        $siswa->nama = $request->nama;
        $siswa->tanggal_masuk = $request->tanggal_masuk;
        $siswa->tanggal_lahir = $request->tanggal_lahir;
        $siswa->tanggal_pembayaran = $request->tanggal_pembayaran;
        $siswa->tempat_lahir = $request->tempat_lahir;
        $siswa->nama_ayah = $request->nama_ayah;
        $siswa->nama_ibu = $request->nama_ibu;
        $siswa->status = $siswa->status;
        $siswa->no_wali_1 = $request->no_wali_1;
        $siswa->no_wali_2 = $request->no_wali_2;
        $siswa->save();

        return redirect('siswa')->with('status', 'Data Berhasil Ditambahkan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function konfirmasiPembayaran(Request $request)
    {
        $siswa = Siswa::findorfail($request->id);
        if ($request->konfirmasi == 'yes') {
            $siswa->konfirmasi_pembayaran = auth()->user()->id;
            $siswa->save();
        } else {
            $siswa->konfirmasi_pembayaran = 'no';
            $siswa->save();
        }

        return redirect()->back()->with('status', 'Pembayaran Berhasil Dikonfirmasi');
    }

    public function changeLevel(Request $request)
    {
        $siswa = Siswa::findorfail($request->id);
        $siswa->level = $request->level;
        $siswa->save();

        return $siswa;
    }

    public function insertNote(Request $request)
    {
        //dd($request->all());
        $siswa = Siswa::findorfail($request->id);
        $siswa->keterangan = $request->note;
        $siswa->save();

        return redirect('siswa')->with('status', 'Note Berhasil Ditambahkan');
    }

    public function changeStatus(Request $request, $id)
    {
        $siswa = Siswa::findorfail($id);
        $siswa->status = $request->status;
        if ($request->status == 'keluar') {
            $siswa->tanggal_lulus = Carbon::now();
        }
        $siswa->save();

        return $request->all();
    }

    public function importSiswa(Request $request)
    {
        Excel::import(new SiswaImport(), $request->file('file'));

        return redirect()->back()->with('status', 'Import Data Berhasil');
    }

    public function exportSiswa()
    {
        return Excel::download(new SiswaExport(), 'Data Siswa.xlsx');
    }
}
