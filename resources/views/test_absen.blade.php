<!DOCTYPE html>
<html>
<head>
    <title>Test Absen Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Form Input Absen</h1>
    
    @if(session('success'))
        <div style="color: green; padding: 10px; border: 1px solid green;">
            {{ session('success') }}
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
    
    <form action="{{ route('absen.store') }}" method="POST">
        @csrf
        <div>
            <label>Siswa ID:</label>
            <input type="number" name="siswa_id" value="1" required>
        </div>
        <div>
            <label>Year:</label>
            <input type="number" name="year" value="2025" required>
        </div>
        <div>
            <label>Month (test with leading zero):</label>
            <input type="text" name="month" value="09" min="1" max="12" required>
            <small>Testing with "09" string format</small>
        </div>
        <div>
            <label>Date:</label>
            <input type="number" name="date" value="17" min="1" max="31" required>
        </div>
        <div>
            <label>Status:</label>
            <select name="status" required>
                <option value="">-- Pilih --</option>
                <option value="masuk" selected>Masuk</option>
                <option value="cuti">Cuti</option>
            </select>
        </div>
        <div>
            <label>Pertemuan:</label>
            <input type="number" name="pertemuan" value="5">
        </div>
        <div>
            <button type="submit">Submit Test</button>
        </div>
    </form>
</body>
</html>
