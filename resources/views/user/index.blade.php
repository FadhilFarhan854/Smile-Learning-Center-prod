@extends('layout.main')

@section('content')

<div class="container-fluid">
    
    <!-- Page Heading -->
    <div class="d-flex justify-content-start mb-4">
        <h1 class="h3 mb-2 text-gray-800">Data User</h1><button class="btn btn-info ml-3" id="print"><i class="fa fa-print"></i> Print</button>
        <a href="{{url('export-user')}}" class="btn btn-success ml-3"><i class="fa fa-download"></i> Export</a>
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
            <a href="{{route('user.create')}}" class="btn btn-success">Tambah User</a>
            @endif
            
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>NIK</th>
                            <th>No. Rekening</th>
                            <th>Tanggal Masuk</th>
                            @if (auth()->user()->role != 'administrator 2')
                            <th>Action</th>
                            @endif
                            
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>NIK</th>
                            <th>No. Rekening</th>
                            <th>Tanggal Masuk</th>
                            @if (auth()->user()->role != 'administrator 2')
                            <th>Action</th>
                            @endif
                        </tr> 
                    </tfoot>
                    <tbody>
                        @foreach ($user as $item)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td>{{$item->email}}</td>
                            <td>{{ucwords($item->role)}}</td>
                            <td>{{$item->nik}}</td>
                            <td>{{$item->rekening}}</td>
                            <td>{{formattedDate($item->tanggal_masuk)}}</td>
                            @if (auth()->user()->role != 'administrator 2')
                            <td>
                                <div class="d-flex justify-content-start">
                                    <a href="{{route('user.edit', $item->id)}}" class="btn btn-info">Edit</a>
                                    <form class="ml-3" action="{{route('user.destroy', $item->id)}}" method="post">
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
    $('#change-guru').on('change', function () {
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
    $('#change-admin').on('change', function () {
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