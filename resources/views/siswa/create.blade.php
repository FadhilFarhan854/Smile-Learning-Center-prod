@extends('layout.main')

@section('content')

<div class="container-fluid">
    
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Siswa</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="col-lg-6 offset-lg-2">
                <form action="{{route('siswa.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="required">Kelas</label>
                        <select name="kelas_id" class="form-control" required>
                            <option value="">-- PILIH KELAS --</option>
                            @foreach ($kelas as $item)
                            <option value="{{$item->id}}">{{$item->unit->nama}} : {{$item->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="required">Nama Siswa</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="required">NIM</label>
                                <input type="number" class="form-control" name="nim" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="required">Tanggal Masuk</label>
                                <input type="date" class="form-control" name="tanggal_masuk" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="required">Tanggal Pembayaran</label>
                                <input type="date" class="form-control" name="tanggal_pembayaran" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="required">Tempat Lahir</label>
                                <input type="text" class="form-control" name="tempat_lahir" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="required">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tanggal_lahir" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="required">Nama Ayah</label>
                                <input type="text" class="form-control" name="nama_ayah" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="required">Nama Ibu</label>
                                <input type="text" class="form-control" name="nama_ibu" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Telp/HP Wali Murid 1</label>
                                <input type="text" class="form-control" name="no_wali_1" >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label >Telp/HP Wali Murid 2</label>
                                <input type="text" class="form-control" name="no_wali_2" >
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

