<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Exports\KelasExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::all();
        $admin = User::where('role', 'admin')->where('status', 'aktif')->get();
        $guru = User::where('role', 'motivator')->where('status', 'aktif')->get();
        
        return view('kelas.index', compact('kelas', 'guru', 'admin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $admin = User::where('role', 'admin')->get();
        $guru = User::where('role', 'motivator')->get();
        $unit = auth()->user()->unitView();
        
        return view('kelas.create', compact('admin', 'guru', 'unit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $kelas = new Kelas;
        $kelas->nama = $request->nama;
        $kelas->unit_id = $request->unit;
        $kelas->user_id = $request->admin;
        $kelas->guru_id = $request->guru;
        $kelas->save();

        return redirect('kelas')->with('status', 'Kelas Berhasil Ditambahkan');
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
        $admin = User::where('role', 'admin')->get();
        $guru = User::where('role', 'motivator')->get();
        $kelas = Kelas::findorfail($id);
        $unit = auth()->user()->unitView();
        
        return view('kelas.edit', compact('admin', 'guru', 'kelas', 'unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //dd($request->all());
        $kelas = Kelas::findorfail($id);
        $kelas->nama = $request->nama;
        $kelas->unit_id = $request->unit;
        $kelas->user_id = $request->admin;
        $kelas->guru_id = $request->guru;
        $kelas->save();

        return redirect('kelas')->with('status', 'Kelas Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::findorfail($id);
        $siswa = $kelas->siswa;

        if (count($siswa) > 0) {
            return redirect()->back()->with('error', 'Tidak Bisa Menghapus Kelas, Kelas Masih Memiliki Siswa Aktif');
        } else {
            Kelas::destroy($id);

            return redirect()->back()->with('status', 'Kelas Berhasil dihapus');
        }
    }

    public function changeGuru(Request $request)
    {
        $kelas = Kelas::findorfail($request->id);
        $kelas->guru_id = $request->guru;
        $kelas->save();

        $guru = $kelas->guru;
        return response()->json([
            'id' => $request->id,
            'guru' => $guru->name
        ]);
    }

    public function changeAdmin(Request $request)
    {
        $kelas = Kelas::findorfail($request->id);
        $kelas->user_id = $request->admin;
        $kelas->save();

        $admin = $kelas->user;
        return response()->json([
            'id' => $request->id,
            'admin' => $admin->name
        ]);
    }

    public function exportKelas() 
    {
        return Excel::download(new KelasExport, 'Data Kelas.xlsx');
    }
}
