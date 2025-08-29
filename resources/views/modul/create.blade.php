@extends('layout.main')

@section('content')

<div class="container-fluid">
    
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Modul</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="col-lg-6 offset-lg-2">
                <form action="{{route('modul.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="required">Nama Modul</label>
                        <input type="text" class="form-control " name="nama" required>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="required">Level</label>
                                <select name="level" id="" class="form-control" required>
                                    <option value="">-- PILIH LEVEL --</option>
                                    @for ($i = 1; $i < 10; $i++)
                                    <option value="{{$i}}">{{$i}}</option> 
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="required">Kategori</label>
                                <select name="kategori" id="" class="form-control" required>
                                    <option value="">-- PILIH KATEGORI --</option>
                                    <option value="baca">Baca</option>
                                    <option value="tulis">Tulis</option>
                                    <option value="hitung">Hitung</option>
                                    <option value="modul SD">Modul SD</option>
                                    <option value="english">English</option>
                                    <option value="iqro">Iqro</option>
                                    <option value="daftar">Set Pendaftaran</option>
                                    <option value="lain">Lainnya</option>
                                    <option value="verbal">English Verbal</option>
                                    <option value="sempoa">Sempoa Intro</option>
                                    <option value="iq">IQ Meet</option>
                                    <option value="aritmatika">Aritmatika</option>
                                    <option value="juara">Anak Juara</option>
                                    <option value="ortu">Modul Orangtua</option>
                                    <option value="cryon">Cryon</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>
<!-- /.container-fluid -->
@endsection

