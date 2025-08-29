@extends('layout.main')

@section('content')

<div class="container-fluid">
    
    <!-- Page Heading -->
    <div class="d-flex justify-content-start">
        <h1 class="h3 mb-2 text-gray-800">Data Modul</h1><button class="btn btn-info ml-3" id="print"><i class="fa fa-print"></i> Print</button>
        <a href="{{url('export-modul')}}" class="btn btn-success ml-3"><i class="fa fa-download"></i> Export</a>
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
            <a href="{{route('modul.create')}}" class="btn btn-success">Tambah Modul</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Level</th>
                            <th>Kategori</th>
                            @if (auth()->user()->role != 'administrator 2')
                            <th>Action</th>
                            @endif
                            <th>Stock</th>
                           {{-- <th>Ketersediaan</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($modul as $item)
                        <tr>
                            <td>{{$item->nama}}</td>
                            <td>{{$item->level}}</td>
                            <td>{{ucwords($item->kategori)}}</td>
                            @if (auth()->user()->role != 'administrator 2')
                            <td>
                                <div class="d-flex justify-content-start">
                                    <a href="{{route('modul.edit', $item->id)}}" class="btn btn-info">Edit</a>
                                    <form class="ml-3" action="{{route('modul.destroy', $item->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger" onclick="return confirm('Yakin Hapus Data?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                            @endif
                            <td class="d-flex justify-content-around">
                                @if (auth()->user()->role != 'administrator 2')
                                <a data-toggle="modal" class="btn transparent-button kurang-stock m-0 p-0" data-id="{{$item->id}}" data-nama="{{$item->nama}}"><i class="fa fa-minus text-primary"></i></a>
                                @endif
                                {{$item->countStock()}} 
                                @if (auth()->user()->role != 'administrator 2')
                                <a data-toggle="modal" class="btn transparent-button tambah-stock m-0 p-0" data-id="{{$item->id}}" data-nama="{{$item->nama}}"><i class="fa fa-plus text-primary"></i></a>
                                @endif
                            </td>
                            {{--<td>
                                <div class="toggle-container">
                                    <label class="switch">
                                        <input type="checkbox" id="toggleSwitch-{{$item->id}}" {{$item->status == 'Tersedia' ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                    <span id="toggleStatus-{{$item->id}}">{{$item->status == 'Tersedia' ? 'Tersedia' : 'Tidak' }}</span>
                                </div>
                            </td>--}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>
<!-- /.container-fluid -->
<!-- Modal Add Modul -->
@foreach ($modul as $item)
<div class="modal fade" id="tambah-stock-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('tambah-stock/'.$item->id)}}" method="post">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="required">Jumlah</label>
                        <input type="number" name="stock" class="form-control" required min="0">
                    </div>
                    <input type="hidden" name="id" value="{{$item->id}}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>   
<!-- / Modal Tambah Modul -->
<!-- Modal Kurang Stock -->
<div class="modal fade" id="kurang-stock-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title-kurang" id="exampleModalLabel">Modal Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('kurang-stock/'.$item->id)}}" method="post">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="required">Jumlah</label>
                        <input type="number" name="stock" class="form-control" required min="0">
                    </div>
                    <input type="hidden" name="id" value="{{$item->id}}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>   
@endforeach
<!--/Modal Kurang Stock -->

@endsection

@section('script')
@foreach ($modul as $item)
{{--<script>
    $('#toggleSwitch-{{$item->id}}').change(function() {
        var status = this.checked ? 'Tersedia' : 'Tidak';
        var id = '{{$item->id}}';
        var csrfToken = "{{csrf_token()}}"

        $('#toggleStatus-{{$item->id}}').text(status);

        $.ajax({
            url: 'change-tersedia/'+id,
            method: 'put',
            headers: {
                'X-CSRF-TOKEN':csrfToken
            },
            data: {
                status:status
            },
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                console.log(error);
            }

        })
    });
    
</script> --}}
<script>
    $('.tambah-stock').on('click', function(){
        var id = $(this).data('id');
        var nama = $(this).data('nama');

        $('.modal-title').text('Tambah Stock '+nama);

        $('#tambah-stock-'+id).modal('show');
    })

    $('.kurang-stock').on('click', function(){
        var id = $(this).data('id');
        var nama = $(this).data('nama');

        $('.modal-title-kurang').text('Kurangi Stock '+nama);

        $('#kurang-stock-'+id).modal('show');
    })
</script>
@endforeach
<script src="{{asset('printThis-master/printThis.js')}}"></script>
<script>
    $('#print').on('click', function() {
        $("#print-this").printThis();
    })
    
</script>
@endsection