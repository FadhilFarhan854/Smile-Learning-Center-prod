@extends('layout.main')

@section('content')

<div class="container-fluid">
    
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Modul</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="col-lg-6 offset-lg-2">
                <form action="{{route('modul.update', $modul->id)}}" method="post">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label class="required">Nama Modul</label>
                        <input type="text" class="form-control " name="nama" required value="{{$modul->nama}}"> 
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="required">Level</label>
                                <select name="level" id="" class="form-control" required>
                                    <option value="">-- PILIH LEVEL --</option>
                                    @for ($i = 1; $i < 10; $i++)
                                    <option value="{{$i}}" {{$modul->level == $i ? 'selected' : ''}}>{{$i}}</option> 
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="required">Kategori</label>
                                <select name="kategori" id="" class="form-control" required>
                                    <option value="">-- PILIH KATEGORI --</option>
                                    <option value="baca" {{$modul->kategori == 'baca' ? 'selected' : ''}}>Baca</option>
                                    <option value="tulis" {{$modul->kategori == 'tulis' ? 'selected' : ''}}>Tulis</option>
                                    <option value="hitung" {{$modul->kategori == 'hitung' ? 'selected' : ''}}>Hitung</option>
                                    <option value="modul SD" {{$modul->kategori == 'modul SD' ? 'selected' : ''}}>Modul SD</option>
                                    <option value="english" {{$modul->kategori == 'english' ? 'selected' : ''}}>English</option>
                                    <option value="iqro" {{$modul->kategori == 'iqro' ? 'selected' : ''}}>Iqro</option>
                                    <option value="daftar" {{$modul->kategori == 'daftar' ? 'selected' : ''}}>Set Pendaftaran</option>
                                    <option value="lain" {{$modul->kategori == 'lain' ? 'selected' : ''}}>Lainnya</option>
                                    <option value="verbal" {{$modul->kategori == 'verbal' ? 'selected' : ''}}>English Verbal</option>
                                    <option value="sempoa" {{$modul->kategori == 'sempoa' ? 'selected' : ''}}>Sempoa Intro</option>
                                    <option value="iq" {{$modul->kategori == 'iq' ? 'selected' : ''}}>IQ Meet</option>
                                    <option value="aritmatika" {{$modul->kategori == 'aritmatika' ? 'selected' : ''}}>Aritmatika</option>
                                    <option value="juara" {{$modul->kategori == 'juara' ? 'selected' : ''}}>Anak Juara</option>
                                    <option value="ortu" {{$modul->kategori == 'ortu' ? 'selected' : ''}}>Modul Orangtua</option>
                                    <option value="cryon" {{$modul->kategori == 'cryon' ? 'selected' : ''}}>Cryon</option>
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

