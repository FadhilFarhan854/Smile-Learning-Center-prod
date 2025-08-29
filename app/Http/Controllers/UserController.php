<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UserExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user()->userView();

        return view('user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required'],
            'nik' => ['required'],
            'rekening' => ['required'],
            'tanggal_masuk' => ['required'] 
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('user.create')  
                ->withErrors($validator)
                ->withInput();
        }

        $user = new User;
        $user->name = $request->nama;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->nik = $request->nik;
        $user->rekening = $request->rekening;
        $user->tanggal_masuk = $request->tanggal_masuk;
        $user->save();
        
        return redirect('user')->with('status', 'Data Berhasil Ditambahkan');
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
        $user = User::findorfail($id);

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required'],
            'nik' => ['required'],
            'rekening' => ['required'],
            'tanggal_masuk' => ['required'] 
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('user.edit', $id)  
                ->withErrors($validator)
                ->with('error', "Error");
        }

        $user = User::findorfail($id);
        $user->name = $request->nama;
        $user->email = $request->email;
        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role;
        $user->nik = $request->nik;
        $user->rekening = $request->rekening;
        $user->tanggal_masuk = $request->tanggal_masuk;
        $user->save();

        return redirect('user')->with('status', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findorfail($id);

        $user->status = 'non-aktif';
        $user->save();

        return redirect('user')->with('status', 'Data Berhasil Dihapus');
    }

    public function exportUser() 
    {
        return Excel::download(new UserExport, 'Data User.xlsx');
    }
}
