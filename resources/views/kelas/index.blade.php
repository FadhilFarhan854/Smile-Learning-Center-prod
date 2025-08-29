@extends('layout.main')

@section('content')

<div class="container-fluid">
    
    <!-- Page Heading -->
    <div class="d-flex justify-content-start">
        <h1 class="h3 mb-2 text-gray-800">Data Kelas</h1><button class="btn btn-info ml-3" id="print"><i class="fa fa-print"></i> Print</button>
        <a href="{{url('export-kelas')}}" class="btn btn-success ml-3"><i class="fa fa-download"></i> Export</a>
    </div>
    @if (session('status'))
    <div class="alert alert-success">
        {{session('status')}}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">
        {{session('error')}}
    </div>
    @endif
    <!-- DataTales Example -->
    <div class="card shadow mb-4" id="print-this">
        <div class="card-header py-3">
            @if (auth()->user()->role != 'administrator 2')
            <a href="{{route('kelas.create')}}" class="btn btn-success">Tambah Kelas</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th>Nama</th>
                            <th>Admin</th>
                            <th>Guru</th>
                            <th>Jumlah Siswa</th>
                            @if (auth()->user()->role != 'administrator 2')
                            <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Unit</th>
                            <th>Nama</th>
                            <th>Admin</th>
                            <th>Guru</th>
                            <th>Jumlah Siswa</th>
                            @if (auth()->user()->role != 'administrator 2')
                            <th>Action</th>
                            @endif
                        </tr> 
                    </tfoot>
                    <tbody>
                        @foreach ($kelas as $item)
                        <tr>
                            <td>{{$item->unit->nama}}</td>
                            <td>{{$item->nama}}</td>
                            <td id="admin-{{$item->id}}">
                                @if (auth()->user()->role != 'administrator 2')
                                @if (isset($item->user->name))
                                <span class="{{$item->user->status === 'non-aktif' ? 'text-danger' : ''}}">{{$item->user->name}}</span>
                                @else
                                <select name="admin" class="form-control change-admin" data-id="{{$item->id}}">
                                    <option value="">-- Pilih Admin --</option>
                                    @foreach ($admin as $ad)
                                    <option value="{{$ad->id}}">{{$ad->name}}</option>
                                    @endforeach
                                </select>
                                @endif
                                @else
                                <span class="{{isset($item->user->status) && $item->user->status === 'non-aktif' ? 'text-danger' : ''}}">{{$item->user->name ?? '--'}}</span>
                                @endif
                            </td>
                            <td id="guru-{{$item->id}}">
                                @if (auth()->user()->role != 'administrator 2')
                                @if (isset($item->guru->name))
                                <span class="{{$item->guru->status === 'non-aktif' ? 'text-danger' : ''}}">{{$item->guru->name}}</span>
                                @else
                                <select name="guru" class="form-control change-guru" data-id="{{$item->id}}">
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach ($guru as $gr)
                                    <option value="{{$gr->id}}">{{$gr->name}}</option>
                                    @endforeach
                                </select>
                                @endif
                                @else
                                <span class="{{isset($item->guru->status) && $item->guru->status === 'non-aktif' ? 'text-danger' : ''}}">{{$item->guru->name ?? '--'}}</span>
                                @endif
                            </td>
                            <td>{{$item->jumlahSiswa()}}</td>
                            @if (auth()->user()->role != 'administrator 2')
                            <td>
                                <div class="d-flex justify-content-start">
                                    <a href="{{route('kelas.edit', $item->id)}}" class="btn btn-info">Edit</a>
                                    <form class="ml-3" action="{{route('kelas.destroy', $item->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger" onclick="return confirm('Yakin Hapus Data?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>
<!-- /.container-fluid -->
@endsection

@section('script')
<script>
    $('.change-guru').on('change', function () {
        var userConfirm = confirm('Yakin ganti item?');
        
        if (userConfirm) {
            var id = $(this).data('id');
            var guru = $(this).val();
            var csrfToken ="{{ csrf_token() }}";
            
            $.ajax({
                url: 'change-guru',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN' : csrfToken
                },
                data: {
                    id:id,
                    guru:guru
                },
                success: function (response) {
                    $('#guru-' + response.id).html('<span>'+response.guru+'</span>');
                },
                error: function (error) {
                    console.error(error);
                }
            })
        }
        
    })
</script>
<script>
    $('.change-admin').on('change', function () {
        var confirmUser = confirm('Yakin ganti item?');

        if (confirmUser) {
            var id = $(this).data('id');
            var admin = $(this).val();
            var csrfToken = '{{csrf_token()}}'

            $.ajax({
                url:'change-admin',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN' : csrfToken
                },
                data: {
                    id:id,
                    admin:admin
                },
                success: function(response) {
                    $('#admin-' + response.id).html('<span>' + response.admin + '</span>');
                },
                error: function (error) {
                    console.error(error);
                }
            })
        }
    })
</script>
<script src="{{asset('printThis-master/printThis.js')}}"></script>
<script>
    $('#print').on('click', function() {
        $("#print-this").printThis();
    })
    
</script>
@endsection