@extends('layout.main')

@section('content')

<div class="container-fluid">
    
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Kelas</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="col-lg-6 offset-lg-2">
                <form action="{{route('kelas.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label >Unit</label>
                        <select name="unit" id="" class="form-control">
                            <option value="">-- PILIH UNIT --</option>
                            @foreach ($unit as $item)
                            <option value="{{$item->id}}">{{$item->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="required">Nama Kelas</label>
                        <input type="text" class="form-control " name="nama" required>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label >Admin</label>
                                <select name="admin" id="" class="form-control">
                                    <option value="">-- PILIH ADMIN --</option>
                                    @foreach ($admin as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label >Guru</label>
                                <select name="guru" id="" class="form-control">
                                    <option value="">-- PILIH GURU --</option>
                                    @foreach ($guru as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
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

