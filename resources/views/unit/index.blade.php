@extends('layout.main')

@section('content')

<div class="container-fluid">
    
    <!-- Page Heading -->
    <div class="d-flex justify-content-start">
        <h1 class="h3 mb-2 text-gray-800">Data Unit</h1><button class="btn btn-info ml-3" id="print"><i class="fa fa-print"></i> Print</button>
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
            <a href="{{route('unit.create')}}" class="btn btn-success">Tambah Unit</a>
            <a href="{{url('export-unit')}}" class="btn btn-success ml-3"><i class="fa fa-download"></i> Export</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jumlah Kelas</th>
                            @if (auth()->user()->role != 'administrator 2')
                            <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unit as $item)
                        <tr>
                            <td>{{$item->nama}}</td>
                            <td>{{$item->jumlahKelas()}}</td>
                            @if (auth()->user()->role != 'administrator 2')
                            <td>
                                <div class="d-flex justify-content-start">
                                    <a href="{{route('unit.edit', $item->id)}}" class="btn btn-info">Edit</a>
                                    <form class="ml-3" action="{{route('unit.destroy', $item->id)}}" method="post">
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
<script src="{{asset('printThis-master/printThis.js')}}"></script>
<script>
    $('#print').on('click', function() {
        $("#print-this").printThis();
    })
    
</script>
@endsection
