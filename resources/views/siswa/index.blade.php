@extends('layout.main')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-flex justify-content-start mb-4">
            <h1 class="h3 mb-2 text-gray-800">Data Siswa</h1>
            @if (auth()->user()->role != 'administrator 2' &&
                    auth()->user()->role != 'admin' &&
                    auth()->user()->role != 'motivator' &&
                    auth()->user()->role != 'guru')
                <button class="btn btn-info ml-3" id="print"><i class="fa fa-print"></i> Print</button>
                <a class="btn btn-success ml-3 import"><i class="fa fa-upload"></i> Import</a>
                <a href="{{ url('export-siswa') }}" class="btn btn-success ml-3"><i class="fa fa-download"></i> Export</a>
            @endif
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <!-- DataTales Example -->
        <div class="card shadow mb-4" id="print-this">
            <div class="card-header py-3 ">
                <div class="row justify-content-between">
                    <div class="col-md-4">
                        @if (auth()->user()->role != 'administrator 2')
                            <a href="{{ route('siswa.create') }}" class="btn btn-success">Tambah Siswa</a>
                        @endif
                    </div>

                    <div class="col-md-8">
                        <form action="{{ route('siswa.index') }}" method="get">
                            @csrf
                            @method('get')
                            <div style="display: flex;">
                                <label>Cari Siswa (Global): </label>
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
                                {{-- <th>Tgl Pembayaran</th> --}}
                                <th>Tgl Masuk</th>
                                <th class="td-min">Lvl</th>
                                {{-- <th>Baca</th>
                                <th>Tulis</th>
                                <th>Hitung</th>
                                <th>Modul SD</th>
                                <th>English</th>
                                <th>Iqro</th>
                                <th>Modul Lain</th> --}}
                                <th>Status</th>
                                <th>Keterangan</th>
                                @if (auth()->user()->role != 'administrator 2')
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($siswa as $item)
                                <tr>
                                    <td>{{ $item->nim }}</td>
                                    <td><a href="{{ route('siswa.show', $item->id) }}">{{ $item->nama }}</a></td>
                                    <td>{{ $item->kelas->nama }}</td>
                                    <td>
                                        <span
                                            class="{{ optional($item->kelas->guru)->status === 'non-aktif' ? 'text-danger' : '' }}">
                                            {{ optional($item->kelas->guru)->name ?? '--' }}
                                        </span> /
                                        <span
                                            class="{{ optional($item->kelas->user)->status === 'non-aktif' ? 'text-danger' : '' }}">
                                            {{ optional($item->kelas->user)->name ?? '--' }}
                                        </span>
                                    </td>

                                    {{--  <td>
                                    @if (auth()->user()->role != 'administrator 2' && auth()->user()->role != 'admin' && auth()->user()->role != 'guru')
                                    @if ($item->konfirmasi_pembayaran == 'no')
                                    {{$item->tanggal_pembayaran != null ? formattedDate($item->tanggal_pembayaran) : ''}}{!! $item->tanggal_pembayaran != null ? '<br>' : '' !!}
                                    <a data-toggle="modal" data-target="pay-confirm-modal-{{$item->id}}" data-id="{{$item->id}}" data-nama="{{$item->nama}}" class="pay-cnfrm pay-confirm">Klik Untuk Verifikasi</a>
                                    @else
                                    {{formattedDate($item->tanggal_pembayaran)}}<br>
                                    <span class="cnfrmd">Sudah diverifikasi</span> <span class="cnfrmd-user">{{ucwords($user->where('id', $item->konfirmasi_pembayaran)->first()->name)}}</span>
                                    @endif
                                    @else
                                    @if ($item->konfirmasi_pembayaran == 'no')
                                    <span class="not-cnfrmd">Belum Dikonfirmasi</span>
                                    @else
                                    {{formattedDate($item->tanggal_pembayaran)}}<br>
                                    <span class="cnfrmd">Sudah diverifikasi</span> <span class="cnfrmd-user">{{ucwords($user->where('id', $item->konfirmasi_pembayaran)->first()->name)}}</span>
                                    @endif
                                    @endif
                                    
                                </td> --}}
                                    <td>{{ formattedDate($item->tanggal_masuk) }}</td>
                                    <td>
                                        @if (auth()->user()->role != 'administrator 2' && auth()->user()->role != 'admin' && auth()->user()->role != 'guru')
                                            <select name="level" id="change-level-{{ $item->id }}"
                                                class="form-control" data-id="{{ $item->id }}">
                                                <option value="" {{ $item->level != null ? 'disabled' : '' }}>N/A
                                                </option>
                                                @for ($i = 1; $i < 10; $i++)
                                                    <option value="{{ $i }}"
                                                        {{ $i < $item->level ? 'disabled' : '' }}
                                                        {{ $item->level == $i ? 'selected' : '' }}>{{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                        @else
                                            <span>{{ $item->level ?? 'N/A' }}</span>
                                        @endif
                                    </td>
                                    {{-- <td>{{$item->latestmodul('baca')->nama ?? '--'}}</td>
                                <td>{{$item->latestmodul('tulis')->nama ?? '--'}}</td>
                                <td>{{$item->latestmodul('hitung')->nama ?? '--'}}</td>
                                <td>{{$item->latestmodul('modul SD')->nama ?? '--'}}</td>
                                <td>{{$item->latestmodul('english')->nama ?? '--'}}</td>
                                <td>{{$item->latestmodul('iqro')->nama ?? '--'}}</td>
                                <td>{{$item->latestmodul('lain')->nama ?? '--'}}</td> --}}
                                    <td>
                                        {{-- @if (auth()->user()->role != 'administrator 2')

                                    @if ($item->konfirmasi_pembayaran == 'no')
                                    <span class="not-cnfrmd">Menunggu Pembayaran Diverifikasi</span>
                                    @else
                                    
                                    @if ($item->status == 'baru')
                                    <select name="status" data-id="{{$item->id}}" class="form-control change-status select-special customa-select">
                                        <option value="baru" >Baru</option>
                                        <option value="aktif" {{$item->status == 'aktif' ? 'selected' : ''}}>Aktif</option>
                                    </select>
                                    
                                    @else
                                    <select name="status" data-id="{{$item->id}}" class="form-control change-status select-special">
                                        <option value="">-- PILIH --</option>
                                        <option value="aktif" {{$item->status == 'aktif' ? 'selected' : ''}}>Aktif</option>
                                        <option value="cuti" {{$item->status == 'cuti' ? 'selected' : ''}}>Cuti</option>
                                        <option value="keluar" {{$item->status == 'keluar' ? 'selected' : ''}}>Keluar</option>
                                        <option value="lulus" {{$item->status == 'lulus' ? 'selected' : ''}}>Lulus</option>
                                    </select>
                                    @endif

                                    @endif

                                    @else
                                    
                                    @if ($item->konfirmasi_pembayaran == 'no')
                                    <span class="not-cnfrmd">Menunggu Pembayaran Diverifikasi</span>
                                    @else
                                    <span>{{ucwords($item->status)}}</span>
                                    @endif

                                    @endif --}}
                                        @if (auth()->user()->role != 'administrator 2')
                                            <select name="status" data-id="{{ $item->id }}"
                                                class="form-control change-status select-special">
                                                <option value="">-- PILIH --</option>
                                                <option value="baru">Baru</option>
                                                <option value="aktif" {{ $item->status == 'aktif' ? 'selected' : '' }}>
                                                    Aktif</option>
                                                <option value="cuti" {{ $item->status == 'cuti' ? 'selected' : '' }}>Cuti
                                                </option>
                                                <option value="keluar" {{ $item->status == 'keluar' ? 'selected' : '' }}>
                                                    Keluar</option>
                                                <option value="lulus" {{ $item->status == 'lulus' ? 'selected' : '' }}>
                                                    Lulus</option>
                                            </select>
                                        @else
                                            <span>{{ ucwords($item->status) }}</span>
                                        @endif

                                    </td>
                                    <td>
                                        @if (auth()->user()->role != 'administrator 2' && auth()->user()->role != 'admin' && auth()->user()->role != 'guru')
                                            @if ($item->keterangan == null)
                                                <a data-toggle="modal" data-target="insert-note-{{ $item->id }}"
                                                    data-id="{{ $item->id }}" data-nama="{{ $item->nama }}"
                                                    class="insert-note"><i class="fa fa-plus"></i> Note</a>
                                            @else
                                                {{ $item->keterangan }} <a data-toggle="modal"
                                                    data-target="insert-note-{{ $item->id }}"
                                                    data-id="{{ $item->id }}" data-nama="{{ $item->nama }}"
                                                    class="insert-note"><i class="fa fa-pen"></i></a>
                                            @endif
                                        @else
                                            <span>{{ $item->keterangan }}</span>
                                        @endif
                                    </td>
                                    @if (auth()->user()->role != 'administrator 2')
                                        <td>
                                            <div class="d-flex justify-content-start">
                                                <a href="{{ route('siswa.edit', $item->id) }}"
                                                    class="btn btn-info">Edit</a>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $siswa->links() }}
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

    <!-- Form Change Unit -->
    <form class="ml-3" action="{{ route('siswa.index') }}" method="get" id="unit-form">
        @csrf
    </form>
    <!-- /Form Change Unit -->
    <!-- Form Change Kelas -->
    <form class="ml-3" action="{{ route('siswa.index') }}" method="get" id="kelas-form">
        @csrf
    </form>
    <!-- /Form Change Kelas -->

    @foreach ($siswa as $item)
        <!-- Pay confirm Modal -->
        <div class="modal fade" id="pay-confirm-modal-{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('konfirmasi-pembayaran') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="required">Konfirmasi Pembayaran</label>
                                <select name="konfirmasi" class="form-control">
                                    <option value="">-- PILIH STATUS --</option>
                                    <option value="yes">Pembayaran Sudah Masuk dan Terverifikasi</option>
                                    <option value="no">Pembayaran Belum Terverifikasi</option>
                                </select>
                            </div>
                            <input type="hidden" name="id" id="id-siswa-{{ $item->id }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Konfirmasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Pay confirm Modal -->
        <!-- Insert Note Modal -->
        <div class="modal fade" id="insert-note-{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="insert-note-title" id="exampleModalLabel">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('insert-note') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="required">Tambah Note </label>
                                <textarea name="note" id="" cols="70" class="form-control" rows="5"></textarea>
                            </div>
                            <input type="hidden" name="id" value="{{ $item->id }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Konfirmasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Insert Note Modal -->
    @endforeach
    <!-- /Import Modal -->
    <div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel">Import Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('import-siswa') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Pilih File </label>
                            <input type="file" name="file" required class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Import Modal -->
@endsection

@section('script')
    <script>
        $('.pay-confirm').on('click', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');

            $('.modal-title').text('Verifikasi Pembayaran - ' + nama);
            $('#id-siswa-' + id).val(id);
            $('#pay-confirm-modal-' + id).modal('show');
        });
    </script>
    <script>
        $('.insert-note').on('click', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');

            $('.insert-note-title').text('Tambah Note - ' + nama);
            $('#id-siswa-' + id).val(id);
            $('#insert-note-' + id).modal('show');
        });
    </script>
    <script>
        $('.import').on('click', function() {

            $('#import-modal').modal('show');
        });
    </script>
    @foreach ($siswa as $item)
        <script>
            $('#change-level-{{ $item->id }}').on('change', function() {
                var userConfirmed = confirm('Yakin Ganti Level?');

                if (userConfirmed) {
                    var id = $(this).data('id');
                    var level = $(this).val();
                    var csrfToken = "{{ csrf_token() }}";

                    $.ajax({
                        url: 'change-level',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: {
                            id: id,
                            level: level
                        },
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    })
                }
            })
        </script>
    @endforeach
    <script>
        $('.change-status').on('change', function() {
            var userConfirmed = confirm('Yakin Ganti Status?');

            if (userConfirmed) {
                var id = $(this).data('id');
                var status = $(this).val();
                var csrfToken = "{{ csrf_token() }}";

                $.ajax({
                    url: 'change-status/' + id,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        status: status,
                    },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(error) {
                        console.log(error);
                    }

                });
            }

        })
    </script>
    <script src="{{ asset('printThis-master/printThis.js') }}"></script>
    <script>
        $('#print').on('click', function() {
            $("#print-this").printThis();
        })
    </script>
    <script>
        $('#change-unit').on('change', function() {
            $('#unit-form').submit();
        });

        $('#change-kelas').on('change', function() {
            $('#kelas-form').submit();
        })
    </script>
@endsection
