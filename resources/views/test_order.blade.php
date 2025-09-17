<!DOCTYPE html>
<html>
<head>
    <title>Test Order Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Form Order - Menambahkan Modul ke Siswa</h1>
    
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
    
    <form action="{{ route('order.store') }}" method="POST">
        @csrf
        <div>
            <label>Siswa ID:</label>
            <input type="number" name="siswa" value="{{ old('siswa', '1') }}" required>
        </div>
        <div>
            <label>Month:</label>
            <input type="number" name="month" value="{{ old('month', '9') }}" min="1" max="12" required>
        </div>
        <div>
            <label>Tahun:</label>
            <input type="number" name="tahun" value="{{ old('tahun', '2025') }}" required>
        </div>
        <div>
            <label>Level:</label>
            <input type="number" name="level" value="{{ old('level', '1') }}" min="1" required>
        </div>
        <div>
            <label>Status:</label>
            <select name="status" required>
                <option value="">-- Pilih Status --</option>
                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        
        <h3>Pilih Modul (Opsional):</h3>
        <div>
            <label>Modul Baca:</label>
            <input type="number" name="modul_baca" value="{{ old('modul_baca') }}" placeholder="ID Modul">
        </div>
        <div>
            <label>Modul Tulis:</label>
            <input type="number" name="modul_tulis" value="{{ old('modul_tulis') }}" placeholder="ID Modul">
        </div>
        <div>
            <label>Modul Hitung:</label>
            <input type="number" name="modul_hitung" value="{{ old('modul_hitung') }}" placeholder="ID Modul">
        </div>
        <div>
            <label>Modul Daftar (Test Bug Fix):</label>
            <input type="number" name="daftar" value="{{ old('daftar') }}" placeholder="ID Modul">
            <small>Testing bug fix untuk kategori daftar</small>
        </div>
        
        <div>
            <button type="submit">Submit Test Order</button>
        </div>
    </form>
    
    <hr>
    <h2>Recent Orders (Last 10)</h2>
    @php
        $orders = \App\Models\Order::orderBy('created_at', 'desc')->limit(10)->with(['siswa', 'modul'])->get();
    @endphp
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Siswa</th>
            <th>Modul</th>
            <th>Kategori</th>
            <th>Level</th>
            <th>Status</th>
            <th>Bulan/Tahun</th>
            <th>Created At</th>
        </tr>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->siswa->nama ?? 'N/A' }}</td>
            <td>{{ $order->modul->nama ?? 'No Modul' }}</td>
            <td>{{ $order->kategori }}</td>
            <td>{{ $order->level }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->bulan }}/{{ $order->tahun }}</td>
            <td>{{ $order->created_at }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
