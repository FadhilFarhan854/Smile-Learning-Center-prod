@extends('layout.main')

@section('content')

<div class="container-fluid">
    
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah User</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="col-lg-6 offset-lg-2">
                <form action="{{route('user.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="required">Nama</label>
                        <input type="text" class="form-control  @error('nama') is-invalid @enderror" name="nama" required>
                        @error('nama')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="required">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" required>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="required">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="required">Role</label>
                        <select name="role" id="" class="form-control">
                            <option value="">-- PILIH ROLE --</option>
                            <option value="administrator 1">Administrator 1</option>
                            <option value="administrator 2">Administrator 2</option>
                            <option value="admin">Admin</option>
                            <option value="motivator">Motivator</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="required">NIK</label>
                        <input type="text" class="form-control " name="nik" required>
                    </div>
                    <div class="form-group">
                        <label class="required">No. Rekening</label>
                        <input type="text" class="form-control " name="rekening" required>
                    </div>
                    <div class="form-group">
                        <label class="required">Tanggal Masuk</label>
                        <input type="date" class="form-control " name="tanggal_masuk" required>
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

