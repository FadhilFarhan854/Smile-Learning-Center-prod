<!DOCTYPE html>
<html>
<head>
    <title>Test Modul Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Form Input Modul</h1>
    
    @if(session('status'))
        <div style="color: green; padding: 10px; border: 1px solid green;">
            {{ session('status') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="color: red; padding: 10px; border: 1px solid red;">
            {{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div style="color: red; padding: 10px; border: 1px solid red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('modul.store') }}" method="POST">
        @csrf
        <div>
            <label>Nama Modul:</label>
            <input type="text" name="nama" value="{{ old('nama', 'Test Modul') }}" required>
        </div>
        <div>
            <label>Level:</label>
            <select name="level" required>
                <option value="">-- PILIH LEVEL --</option>
                @for ($i = 1; $i < 10; $i++)
                    <option value="{{$i}}" {{ old('level') == $i ? 'selected' : '' }}>{{$i}}</option> 
                @endfor
            </select>
        </div>
        <div>
            <label>Kategori:</label>
            <select name="kategori" required>
                <option value="">-- PILIH KATEGORI --</option>
                <option value="baca" {{ old('kategori') == 'baca' ? 'selected' : '' }}>Baca</option>
                <option value="tulis" {{ old('kategori') == 'tulis' ? 'selected' : '' }}>Tulis</option>
                <option value="hitung" {{ old('kategori') == 'hitung' ? 'selected' : '' }}>Hitung</option>
                <option value="modul SD" {{ old('kategori') == 'modul SD' ? 'selected' : '' }}>Modul SD</option>
                <option value="english" {{ old('kategori') == 'english' ? 'selected' : '' }}>English</option>
                <option value="iqro" {{ old('kategori') == 'iqro' ? 'selected' : '' }}>Iqro</option>
                <option value="lain" {{ old('kategori') == 'lain' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>
        <div>
            <button type="submit">Submit Test</button>
        </div>
    </form>
    
    <hr>
    <h2>Existing Moduls (Last 10)</h2>
    @php
        $moduls = \App\Models\Modul::orderBy('created_at', 'desc')->limit(10)->get();
    @endphp
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Level</th>
            <th>Status</th>
            <th>Stock</th>
            <th>Created At</th>
        </tr>
        @foreach($moduls as $modul)
        <tr>
            <td>{{ $modul->id }}</td>
            <td>{{ $modul->nama }}</td>
            <td>{{ $modul->kategori }}</td>
            <td>{{ $modul->level }}</td>
            <td>{{ $modul->status ?? 'N/A' }}</td>
            <td>{{ $modul->stock ?? 'N/A' }}</td>
            <td>{{ $modul->created_at }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
