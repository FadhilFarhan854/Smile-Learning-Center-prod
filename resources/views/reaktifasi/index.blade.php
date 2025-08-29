@extends('layout.main')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-flex justify-content-start mb-4">
            <h1 class="h3 mb-2 text-gray-800">Reaktifasi Siswa</h1>
            <span class="ml-3 text-muted">(Siswa dengan status keluar)</span>
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row justify-content-between">
                    <div class="col-md-4">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa Keluar</h6>
                    </div>

                    <div class="col-md-8">
                        <form action="{{ url('reaktifasi') }}" method="get">
                            @csrf
                            @method('get')
                            <div style="display: flex;">
                                <label>Cari Siswa: </label>
                                <input type="text" name="search" class="form-control" id="global-search"
                                    style="flex: 1;" value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary ml-2">Go!</button>
                            </div>
                            <div class="d-flex mt-2">
                                <label class="mt-2">Unit:</label>
                                <select name="unit" id="change-unit" class="form-control ml-2" form="unit-form">
                                    <option value="">All</option>
                                    @foreach ($unit as $ut)
                                        <option value="{{ $ut->id }}" {{ $reqUnit == $ut->id ? 'selected' : '' }}>
                                            {{ $ut->nama }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="kelas" value="{{ $reqKelas }}" form="unit-form">
                                <input type="hidden" name="search" value="{{ request('search') }}" form="unit-form">
                                <label class="mt-2 ml-3">Kelas:</label>
                                <select name="kelas" id="change-kelas" class="form-control ml-2" form="kelas-form">
                                    <option value="">All</option>
                                    @foreach ($kelas as $kl)
                                        <option value="{{ $kl->id }}" {{ $reqKelas == $kl->id ? 'selected' : '' }}>
                                            {{ $kl->nama }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="unit" value="{{ $reqUnit }}" form="kelas-form">
                                <input type="hidden" name="search" value="{{ request('search') }}" form="kelas-form">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Guru/Admin</th>
                                <th>Tgl Masuk</th>
                                <th>Tgl Keluar</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswa as $item)
                                <tr>
                                    <td>{{ $item->nim }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->kelas->nama ?? '-' }}</td>
                                    <td>{{ $item->kelas->user->nama ?? '-' }}</td>
                                    <td>{{ $item->tanggal_masuk ? \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $item->tanggal_lulus ? \Carbon\Carbon::parse($item->tanggal_lulus)->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <span class="badge badge-danger">{{ ucwords($item->status) }}</span>
                                    </td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" 
                                                data-target="#reaktifasi-modal-{{ $item->id }}">
                                            <i class="fas fa-user-plus"></i> Reaktifasi
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada siswa dengan status keluar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $siswa->links() }}
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

    <!-- Form Change Unit -->
    <form class="ml-3" action="{{ url('reaktifasi') }}" method="get" id="unit-form">
        @csrf
    </form>
    <!-- /Form Change Unit -->
    <!-- Form Change Kelas -->
    <form class="ml-3" action="{{ url('reaktifasi') }}" method="get" id="kelas-form">
        @csrf
    </form>
    <!-- /Form Change Kelas -->

    @foreach ($siswa as $item)
        <!-- Reaktifasi Modal -->
        <div class="modal fade" id="reaktifasi-modal-{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="reaktifasiModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reaktifasiModalLabel">Konfirmasi Reaktifasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('reaktifasi/' . $item->id) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin mereaktifasi siswa <strong>{{ $item->nama }}</strong>?</p>
                            <p>Status siswa akan berubah dari <span class="badge badge-danger">Keluar</span> menjadi <span class="badge badge-success">Aktif</span>.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Reaktifasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Reaktifasi Modal -->
    @endforeach
@endsection

@section('script')
    <script>
        $('#change-unit').on('change', function() {
            $('#unit-form').submit();
        });

        $('#change-kelas').on('change', function() {
            $('#kelas-form').submit();
        });
    </script>