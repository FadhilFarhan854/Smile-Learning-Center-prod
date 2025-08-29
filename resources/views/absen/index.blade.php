@extends('layout.main')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Absensi</h1>
        <a href="{{ url('export-absen/' . $month . '/' . $year) }}" class="btn btn-success ml-3"><i class="fa fa-download"></i>
            Export</a>
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
                <div class="row">
                    <div class="d-flex justify-content-around col-lg-8">
                        @for ($i = 1; $i <= 12; $i++)
                            <form action="{{ route('absen.index') }}" method="get">
                                @csrf
                                @method('get')
                                <input type="hidden" name="month" value="{{ $i }}">
                                <input type="hidden" name="tahun" value="{{ $year }}">
                                <button type="submit"
                                    class="btn btn-light {{ $month == $i ? 'active' : '' }}">{{ $months[$i] }}</button>
                            </form>
                        @endfor
                    </div>
                    <div class="col-lg-4 d-flex justify-content-between">
                        <select name="tahun" id="change-tahun" class="form-control mr-4" data-month="{{ $month }}">
                            @for ($i = $year + 1; $i > $year - 4; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                        @if (auth()->user()->role != 'administrator 2' && auth()->user()->role != 'admin' && auth()->user()->role != 'guru')
                            <button id="print" class="btn btn-info"> Print </button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body" id="print-this">
                <div class="row mt-0 pt-0 justify-content-between">
                    <div class="col-lg-5 ">
                        <button type="button" class="transparent-button f-13 p-1  input-absen"><i
                                class="font-small fa fa-check text-success "></i></button> <span class="font-small"> <i
                                class="fa fa-arrow-right"></i> Masuk</span> |
                        <button type="button" class=" font-small badge badge-primary f-10 p-1  border input-absen"><i
                                class="font-small fa fa-clock"></i> </button> <span class="font-small"><i
                                class="fa fa-arrow-right"></i> Cuti</span>
                    </div>
                    <div class="d-flex">
                        <label class="mt-2">Unit:</label>
                        <select name="unit" id="change-unit" class="form-control ml-2" form="unit-form">
                            <option value="">All</option>
                            @foreach ($unit as $ut)
                                <option value="{{ $ut->id }}" {{ $reqUnit == $ut->id ? 'selected' : '' }}>
                                    {{ $ut->nama }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="month" value="{{ $month }}" form="unit-form">
                        <input type="hidden" name="tahun" value="{{ $year }}" form="unit-form">
                        <input type="hidden" name="kelas" value="{{ $reqKelas }}" form="unit-form">
                        <label class="mt-2 ml-3">Kelas:</label>
                        <select name="kelas" id="change-kelas" class="form-control ml-2" form="kelas-form">
                            <option value="">All</option>
                            @foreach ($kelas as $kl)
                                <option value="{{ $kl->id }}" {{ $reqKelas == $kl->id ? 'selected' : '' }}>
                                    {{ $kl->nama }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="month" value="{{ $month }}" form="kelas-form">
                        <input type="hidden" name="tahun" value="{{ $year }}" form="kelas-form">
                        <input type="hidden" name="unit" value="{{ $reqUnit }}" form="kelas-form">

                    </div>
                    <div class="col-lg-2">
                        <div class="d-flex">
                            <form action="{{ route('absen.index') }}" method="get">
                                @csrf
                                @method('get')
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" id="global-search" placeholder="Cari Siswa (Global)">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary ml-2">Go!</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table-absen " width="100%" cellspacing="0">
                        <thead class="mt-5">
                            <tr>
                                <th class="px-44 f-9 th-judul">Nama Siswa</th>
                                @for ($i = 1; $i <= $daysInMonth; $i++)
                                    <th class="px-44 f-9 th-absen">{{ $i }}
                                        <br>{{ $weekMap[\Carbon\Carbon::parse(\Carbon\Carbon::parse($i . '-' . $month . '-' . $year))->dayOfWeek] }}
                                    </th>
                                @endfor
                                <th class="px-44 f-9 th-absen">Total <br> Hadir</th>
                                <th class="px-44 f-9 th-absen">Ket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($siswa as $item)
                                <tr>
                                    <td class="td-judul">{{ $item->nama }}</td>
                                    @foreach ($dateToFill[$item->id] as $key => $it)
                                        @if (auth()->user()->role != 'administrator 2')
                                            <td class="td-absen">
                                                @if ($it['status'] == 'PRE' || $it['status'] == 'POST')
                                                    -
                                                @elseif($it['status'] == 'EMPTY')
                                                    <button type="button"
                                                        class="badge badge-light f-10 p-1 border input-absen"
                                                        data-toggle="modal" data-target="input-absen-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-date="{{ $key }}"
                                                        data-month="{{ $month }}" data-year="{{ $year }}"
                                                        data-nama="{{ $item->nama }}"><i
                                                            class="fa fa-plus text-secondary"></i></button>
                                                @elseif($it['status'] == 'masuk')
                                                    <button type="button"
                                                        class="transparent-button f-13 p-1  input-absen"
                                                        data-toggle="modal" data-target="input-absen-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-date="{{ $key }}"
                                                        data-month="{{ $month }}" data-year="{{ $year }}"
                                                        data-nama="{{ $item->nama }}"><span
                                                            class="text-success border border-success rounded"
                                                            style="font-size:10px; padding:2px">{{ $it['pertemuan'] }}</span></button>
                                                @elseif($it['status'] == 'alfa')
                                                    <button type="button"
                                                        class="transparent-button f-13 p-1  input-absen"
                                                        data-toggle="modal" data-target="input-absen-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-date="{{ $key }}"
                                                        data-month="{{ $month }}" data-year="{{ $year }}"
                                                        data-nama="{{ $item->nama }}"><i
                                                            class="fa fa-times text-danger"></i> </button>
                                                @elseif($it['status'] == 'cuti')
                                                    <button type="button"
                                                        class="badge badge-primary f-10 p-1  border input-absen"
                                                        data-toggle="modal" data-target="input-absen-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-date="{{ $key }}"
                                                        data-month="{{ $month }}" data-year="{{ $year }}"
                                                        data-nama="{{ $item->nama }}"><i class="fa fa-clock"></i>
                                                    </button>
                                                @elseif($it['status'] == 'sakit')
                                                    <button type="button"
                                                        class="badge badge-danger f-10 p-1  border input-absen"
                                                        data-toggle="modal" data-target="input-absen-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-date="{{ $key }}"
                                                        data-month="{{ $month }}" data-year="{{ $year }}"
                                                        data-nama="{{ $item->nama }}"><i
                                                            class="fa fa-stethoscope "></i> </button>
                                                @elseif($it['status'] == 'izin')
                                                    <button type="button"
                                                        class="badge badge-warning f-10 p-1  border input-absen"
                                                        data-toggle="modal" data-target="input-absen-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-date="{{ $key }}"
                                                        data-month="{{ $month }}" data-year="{{ $year }}"
                                                        data-nama="{{ $item->nama }}"><i class="fa fa-calendar"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        @else
                                            <td class="td-absen">
                                                @if ($it == 'PRE' || $it == 'POST')
                                                    -
                                                @elseif($it['status'] == 'EMPTY')
                                                    <button type="button" class="badge badge-light f-10 p-1 border "
                                                        data-toggle="modal" data-target="input-absen-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-date="{{ $key }}"
                                                        data-month="{{ $month }}" data-year="{{ $year }}"
                                                        data-nama="{{ $item->nama }}"><i
                                                            class="fa fa-plus text-secondary"></i></button>
                                                @elseif($it['status'] == 'masuk')
                                                    <button type="button" class="transparent-button f-13 p-1  "
                                                        data-toggle="modal" data-target="input-absen-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-date="{{ $key }}"
                                                        data-month="{{ $month }}" data-year="{{ $year }}"
                                                        data-nama="{{ $item->nama }}"><span
                                                            class="text-success border border-success rounded"
                                                            style="font-size:10px; padding:2px">{{ $it['pertemuan'] }}</span></button>
                                                @elseif($it['status'] == 'alfa')
                                                    <button type="button" class="transparent-button f-13 p-1  "
                                                        data-toggle="modal" data-target="input-absen-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-date="{{ $key }}"
                                                        data-month="{{ $month }}" data-year="{{ $year }}"
                                                        data-nama="{{ $item->nama }}"><i
                                                            class="fa fa-times text-danger"></i> </button>
                                                @elseif($it['status'] == 'cuti')
                                                    <button type="button" class="badge badge-primary f-10 p-1  border "
                                                        data-toggle="modal" data-target="input-absen-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-date="{{ $key }}"
                                                        data-month="{{ $month }}" data-year="{{ $year }}"
                                                        data-nama="{{ $item->nama }}"><i class="fa fa-clock"></i>
                                                    </button>
                                                @elseif($it['status'] == 'sakit')
                                                    <button type="button" class="badge badge-danger f-10 p-1  border "
                                                        data-toggle="modal" data-target="input-absen-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-date="{{ $key }}"
                                                        data-month="{{ $month }}" data-year="{{ $year }}"
                                                        data-nama="{{ $item->nama }}"><i
                                                            class="fa fa-stethoscope "></i> </button>
                                                @elseif($it['status'] == 'izin')
                                                    <button type="button" class="badge badge-warning f-10 p-1  border "
                                                        data-toggle="modal" data-target="input-absen-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-date="{{ $key }}"
                                                        data-month="{{ $month }}"
                                                        data-year="{{ $year }}"
                                                        data-nama="{{ $item->nama }}"><i class="fa fa-calendar"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                    <td class="transparent-button f-13 p-1  input-absen">
                                        {{ $item->countAbsen($month, $year) }}</td>
                                    <td class="transparent-button f-13 p-1  input-absen">
                                        @if ($item->checkAbsenNotes($month, $year))
                                            <button type="button" class="badge badge-warning f-10 p-1  border edit-notes"
                                                data-id="{{ $item->id }}" data-month="{{ $month }}"
                                                data-year="{{ $year }}" data-nama="{{ $item->nama }}"><i
                                                    class="fas fa-sticky-note"></i> </button>
                                        @else
                                            <button type="button" class="badge badge-info f-10 p-1  border input-notes"
                                                data-id="{{ $item->id }}" data-month="{{ $month }}"
                                                data-year="{{ $year }}" data-nama="{{ $item->nama }}"><i
                                                    class="fa fa-plus"></i> </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $siswa->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Form Change Unit -->
    <form class="ml-3" action="{{ route('absen.index') }}" method="get" id="unit-form">
        @csrf
    </form>
    <!-- /Form Change Unit -->
    <!-- Form Change Kelas -->
    <form class="ml-3" action="{{ route('absen.index') }}" method="get" id="kelas-form">
        @csrf
    </form>
    <!-- /Form Change Unit -->
    <!-- /.container-fluid -->

    <!-- Modal Input Absen -->
    @foreach ($siswa as $item)
        <div class="modal fade" id="input-absen-{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('absen.store') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="required">Status Absen</label>
                                <select name="status" id="" class="form-control required" required>
                                    <option value="">-- PILIH --</option>
                                    <option value="masuk">Masuk</option>
                                    <option value="cuti">Cuti</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="required">Pertemuan Ke-</label>
                                <input type="number" name="pertemuan" class="form-control required">
                            </div>
                            <input type="hidden" name="siswa_id" value="{{ $item->id }}">
                            <input type="hidden" name="date" class="date-absen">
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="hidden" name="year" value="{{ $year }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Konfirmasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Modal Input Absen -->

        <!-- Modal Input Notes -->
        <div class="modal fade" id="input-notes-{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title-note" id="exampleModalLabel">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('input-note') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="required">Keterangan</label>
                                <textarea name="keterangan" id="" cols="30" rows="5" class="form-control" required></textarea>
                            </div>
                            <input type="hidden" name="siswa_id" value="{{ $item->id }}">
                            <input type="hidden" name="date" class="date-absen">
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="hidden" name="year" value="{{ $year }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Konfirmasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Modal Input Notes -->

        <!-- Modal Input Notes -->
        <div class="modal fade" id="edit-notes-{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title-note" id="exampleModalLabel">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('input-note') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="required">Keterangan</label>
                                <textarea name="keterangan" id="" cols="30" rows="5" class="form-control" required>{{ optional($item->checkAbsenNotes($month, $year))->keterangan }}</textarea>
                            </div>
                            <input type="hidden" name="siswa_id" value="{{ $item->id }}">
                            <input type="hidden" name="date" class="date-absen">
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="hidden" name="year" value="{{ $year }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-danger"
                                form="order-form-{{ $item->id }}">Hapus</button>
                            <button type="submit" class="btn btn-primary">Konfirmasi</button>
                        </div>
                    </form>
                    <form action="{{ url('delete-note') }}" method="post" id="order-form-{{ $item->id }}">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="id"
                            value="{{ optional($item->checkAbsenNotes($month, $year))->id }}">
                    </form>
                </div>
            </div>
        </div>
        <!-- /Modal Edit Notes -->
    @endforeach
@endsection

@section('script')
    <script>
        $('#change-tahun').on('change', function() {
            var tahun = $(this).val();
            var month = $(this).data('month');
            var csrfToken = "{{ csrf_token() }}"

            $.ajax({
                url: "{{ route('absen.index') }}",
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    tahun: tahun,
                    month: month // Make sure the key matches the controller's parameter
                },
                dataType: 'html', // Expect HTML response
                success: function(response) {
                    // Update the entire HTML of the document
                    document.open();
                    document.write(response);
                    document.close();

                    var newUrl = window.location.pathname + '?tahun=' + tahun + '&month=' + month;
                    window.history.pushState({}, '', newUrl);
                },
                error: function(error) {
                    console.log(error);
                }
            });

        })
    </script>
    @foreach ($siswa as $item)
        <script>
            $(document).ready(function() {
                $('.input-absen').on('click', function() {
                    var id = $(this).data('id');
                    var date = $(this).data('date');
                    var month = $(this).data('month');
                    var year = $(this).data('year');
                    var nama = $(this).data('nama');

                    $('.modal-title').text('Absen - ' + nama + ' : ' + date + '/' + month + '/' + year);
                    $('.date-absen').val(date);
                    $('#input-absen-' + id).modal('show');
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.input-notes').on('click', function() {
                    var id = $(this).data('id');
                    var month = $(this).data('month');
                    var year = $(this).data('year');
                    var nama = $(this).data('nama');

                    $('.modal-title-note').text('Keterangan Absensi - ' + nama);
                    $('#input-notes-' + id).modal('show');
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.edit-notes').on('click', function() {
                    var id = $(this).data('id');
                    var month = $(this).data('month');
                    var year = $(this).data('year');
                    var nama = $(this).data('nama');

                    $('.modal-title-note').text('Keterangan Absensi - ' + nama);
                    $('#edit-notes-' + id).modal('show');
                });
            });
        </script>
    @endforeach
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
