@extends('layout.main')

@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-flex justify-content-start">
            <h1 class="h3 mb-2 text-gray-800">Order </h1>
            @if (auth()->user()->role != 'administrator 2' &&
                    auth()->user()->role != 'admin' &&
                    auth()->user()->role != 'motivator' &&
                    auth()->user()->role != 'guru')
                <button class="btn btn-info ml-3" id="print"><i class="fa fa-print"></i> Print</button>
                <a href="{{ url('export-order/' . $month . '/' . $year) }}" class="btn btn-success ml-3"><i
                        class="fa fa-download"></i> Export</a>
            @endif
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

        <div class="card shadow mb-4" id="print-this">
            <div class="card-header py-3">
                <div class="row">
                    <div class="d-flex justify-content-around col-lg-8">
                        @for ($i = 1; $i <= 12; $i++)
                            <form action="{{ route('order.index') }}" method="get">
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
                            <a id="rekap-toggle" class="text-success text-center"><i
                                    class="fa fa-2x fa-list-alt"></i><br>Rekap</a>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 d-flex justify-content-start ml-3">
                        <a data-toggle="modal" class="siswa-total">Total : {{ $siswasi }}</a>
                        <a data-toggle="modal" class="siswa-aktif ml-3">Aktif : {{ $aktif->count() }}</a>
                        <a data-toggle="modal" class="siswa-cuti ml-3">Cuti : {{ $cuti->count() }}</a>
                        <a data-toggle="modal" class="siswa-keluar ml-3">Keluar : {{ $keluar->count() }}</a>
                        <a data-toggle="modal" class="siswa-baru ml-3 ">Baru : {{ $baru->count() }}</a>
                        <a data-toggle="modal" class="siswa-belum ml-3">Blm Konfirmasi :
                            {{ $siswasi - $aktif->count() - $cuti->count() - $keluar->count()  }}</a> 
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row justify-content-end mb-3">
                    <div class="col-lg-5 d-flex justify-content-end ml-3">
                        <form action="{{ route('order.index') }}" method="get">
                            @csrf
                            @method('get')
                            <div style="display: flex;">
                                <label>Cari Siswa (Global): </label>
                                <input type="text" name="search" class="form-control" id="global-search"
                                    style="flex: 1;">
                                <button type="submit" class="btn btn-primary ml-2">Go!</button>
                            </div>
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="hidden" name="tahun" value="{{ $year }}">
                            <div class="d-flex mt-2">
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
                        </form>


                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>NIM</th>
                                <th>Siswa</th>
                                <th>Status</th>
                                <th>SPP</th>
                                <th>Pembayaran</th>
                                <th>Level</th>
                                <th>Baca</th>
                                <th>Tulis</th>
                                <th>Hitung</th>
                                <th>Modul SD</th>
                                <th>English</th>
                                <th>Iqro</th>
                                <th>Pendaftaran Set</th>
                                <th>Modul Lain</th>
                                <th>English Verbal</th>
                                <th>Sempoa Intro</th>
                                <th>IQ Meet</th>
                                <th>Aritmatika</th>
                                <th>Anak Juara</th>
                                <th>Modul Orangtua</th>
                                <th>Cryon</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach ($siswa as $item)
                                <tr>
                                    <form class="ml-3" action="{{ route('order.store') }}" method="post"
                                        id="order-form-{{ $item->id }}">
                                        @csrf
                                        <td>{{ $item->kelas->nama }}</td>
                                        <td>{{ $item->nim }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>
                                            {{-- @if (optional($item->checkSPP($month, $year))->verified == 'yes') --}}
                                            @if ($item->checkSPP($month, $year) && $item->checkSPP($month, $year)->verified == 'yes')
                                                <span
                                                    class="{{ $item->checkStatus($item->checkSPP($month, $year)->status) }}">{{ ucwords($item->checkSPP($month, $year)->status) }}</span>
                                            @else
                                                <span>Menunggu konfirmasi</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (auth()->user()->role != 'administrator 2')
                                                @if (count($item->spp->where('bulan', $month)->where('tahun', $year)) > 0 &&
                                                        $item->checkSPP($month, $year)->verified == 'yes')
                                                    <span>{{ $item->spp->where('bulan', $month)->where('tahun', $year)->first()->tanggal != null ? formattedDate($item->spp->where('bulan', $month)->where('tahun', $year)->first()->tanggal) : '' }}</span>{!! $item->checkSPP($month, $year)->tanggal != null ? '<br>' : '' !!}
                                                    <span class="cnfrmd">Sudah diverifikasi</span>
                                                @elseif(count($item->spp->where('bulan', $month)->where('tahun', $year)) > 0 &&
                                                        $item->checkSPP($month, $year)->verified == 'no')
                                                    <span>{{ $item->spp->where('bulan', $month)->where('tahun', $year)->first()->tanggal != null ? formattedDate($item->spp->where('bulan', $month)->where('tahun', $year)->first()->tanggal) . ' : ' : '' }}{{ ucwords($item->checkSPP($month, $year)->status) }}
                                                        <a class="input-pembayaran" data-id="{{ $item->id }}"
                                                            data-nama="{{ $item->nama }}"><i class="fa fa-pen"
                                                                style="cursor: pointer"></i></a></span><br>
                                                    <a data-toggle="modal"
                                                        data-target="pay-confirm-modal-{{ $item->id }}"
                                                        data-id="{{ $item->checkSPP($month, $year)->id }}"
                                                        data-nama="{{ $item->nama }}"
                                                        class="pay-cnfrm pay-confirm">Klik Untuk Verifikasi</a>
                                                @elseif(count($item->spp->where('bulan', $month)->where('tahun', $year)) <= 0)
                                                    <a class="purple-cnfrm input-pembayaran"
                                                        data-id="{{ $item->id }}" data-nama="{{ $item->nama }}"><i
                                                            class="fa fa-plus"></i> Pembayaran </a>
                                                @endif
                                            @else
                                                @if (count($item->spp->where('bulan', $month)->where('tahun', $year)) > 0 &&
                                                        $item->checkSPP($month, $year)->verified == 'yes')
                                                    <span>{{ $item->spp->where('bulan', $month)->where('tahun', $year)->first()->tanggal != null ? formattedDate($item->spp->where('bulan', $month)->where('tahun', $year)->first()->tanggal) : '' }}</span>
                                                @else
                                                    <span class="text-danger">Belum dikonfirmasi</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if (auth()->user()->role != 'administrator 2')
                                                @if (count($item->additional->where('bulan', $month)->where('tahun', $year)) > 0)
                                                    {{ $item->additional->where('bulan', $month)->where('tahun', $year)->first()->status }}
                                                    : IDR
                                                    {{ number_format($item->additional->where('bulan', $month)->where('tahun', $year)->first()->biaya) }}
                                                    <a data-toggle="modal" data-target="insert-note-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-nama="{{ $item->nama }}"
                                                        class="insert-note"><i class="fa fa-pen"></i></a>
                                                @else
                                                    <a data-toggle="modal" data-target="insert-note-{{ $item->id }}"
                                                        data-id="{{ $item->id }}" data-nama="{{ $item->nama }}"
                                                        class="insert-note"><i class="fa fa-plus"></i> Pembayaran</a>
                                                @endif
                                            @else
                                                @if (count($item->additional->where('bulan', $month)->where('tahun', $year)))
                                                    <span>{{ $item->additional->where('bulan', $month)->where('tahun', $year)->first()->status }}</span>
                                                @else
                                                    <span class="text-danger">Belum dikonfirmasi</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->first()->level }}
                                            @else
                                                @if (auth()->user()->role != 'administrator 2')
                                                    <select name="level" id="change-level-{{ $item->id }}"
                                                        class="form-control change-level" data-id="{{ $item->id }}"
                                                        form="order-form-{{ $item->id }}">
                                                        <option value=""
                                                            {{ $item->level != null ? 'disabled' : '' }}>N/A</option>
                                                        @for ($i = 1; $i < 10; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ $item->level == $i ? 'selected' : '' }}>
                                                                {{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                @else
                                                    <select name="level" id="change-level-{{ $item->id }}"
                                                        class="form-control change-level" data-id="{{ $item->id }}"
                                                        form="order-form-{{ $item->id }}">
                                                        <option value=""
                                                            {{ $item->level != null ? 'disabled' : '' }}>N/A</option>
                                                        @for ($i = 1; $i < 10; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ $i < $item->level ? 'disabled' : '' }}
                                                                {{ $item->level == $i ? 'selected' : '' }}>
                                                                {{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'baca')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'baca')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="modul_baca" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($item->chooseModul('baca', $modulAll) as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach 
                                                </select>
                                                @if (session('error-baca-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-baca-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'tulis')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'tulis')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="modul_tulis" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'tulis') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach 
                                                </select>
                                                @if (session('error-tulis-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-tulis-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif

                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'hitung')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'hitung')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="modul_hitung" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'hitung') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'color:red' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach 
                                                </select>
                                                @if (session('error-hitung-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-hitung-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'modul SD')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'modul SD')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="modul_sd" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'modul SD') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach 
                                                </select>
                                                @if (session('error-sd-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-sd-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'english')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'english')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="english" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'english') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach 
                                                </select>
                                                @if (session('error-english-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-english-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'iqro')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'iqro')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="iqro" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'iqro') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach 
                                                </select>
                                                @if (session('error-iqro-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-iqro-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'daftar')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'daftar')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="daftar" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'daftar') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach 
                                                </select>
                                                @if (session('error-daftar-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-daftar-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'lain')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'lain')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="lain" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'lain') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach 
                                                </select>
                                                @if (session('error-lain-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-lain-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'verbal')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'verbal')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="verbal" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'verbal') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach 
                                                </select>
                                                @if (session('error-verbal-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-verbal-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'sempoa')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'sempoa')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="sempoa" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'sempoa') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach 
                                                </select>
                                                @if (session('error-sempoa-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-sempoa-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'iq')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'iq')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="iq" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'iq') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach 
                                                </select>
                                                @if (session('error-iq-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-iq-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'aritmatika')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'aritmatika')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="aritmatika" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'aritmatika') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach 
                                                </select>
                                                @if (session('error-aritmatika-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-aritmatika-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'juara')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'juara')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="juara" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'juara') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach
                                                </select>
                                                @if (session('error-juara-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-juara-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'ortu')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'ortu')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="ortu" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'ortu') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach 
                                                </select>
                                                @if (session('error-ortu-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-ortu-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-min">
                                            @if ($orderAll->where('siswa_id', $item->id)->where('kategori', 'cryon')->first())
                                                {{ $orderAll->where('siswa_id', $item->id)->where('kategori', 'cryon')->first()->modul->nama ?? '--' }}
                                            @else
                                                <select name="cryon" id="" class="form-control"
                                                    form="order-form-{{ $item->id }}">
                                                    <option value="">--</option>
                                                    @foreach ($modulAll->where('kategori', 'cryon') as $md)
                                                        <option value="{{ $md->id }}"
                                                            style="{{ $md->countStock() <= 0 ? 'display:none' : '' }}">
                                                            Level {{ $md->level }} : {{ $md->nama }}</option>
                                                    @endforeach
                                                </select>
                                                @if (session('error-cryon-' . $item->id))
                                                    <div class="alert alert-danger p-1">
                                                        {{ session('error-cryon-' . $item->id) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if (auth()->user()->role != 'administrator 2')
                                                <div class="d-flex justify-content-start">
                                                    <input type="hidden" name="month" value="{{ $month }}"
                                                        form="order-form-{{ $item->id }}">
                                                    <input type="hidden" name="tahun" value="{{ $year }}"
                                                        form="order-form-{{ $item->id }}">
                                                    <input type="hidden" name="siswa" value="{{ $item->id }}"
                                                        form="order-form-{{ $item->id }}">
                                                    <input type="hidden" name="status" value="{{ $item->status }}"
                                                        form="order-form-{{ $item->id }}">
                                                    @if ($orderAll->where('siswa_id', $item->id)->first())
                                                        <span>Sudah di Submit</span>
                                                        @if (auth()->user()->role != 'admin' && auth()->user()->role != 'motivator')
                                                            <a href="{{ url('order/edit/' . $item->id . '/' . $month . '/' . $year) }}"
                                                                class="btn btn-info ml-3">Edit</a>
                                                        @endif
                                                    @elseif(!$item->checkSPP($month, $year) || $item->checkSPP($month, $year)->verified == 'no')
                                                        <span class="not-cnfrmd">Pembayaran Belum disetujui
                                                            Administrator</span>
                                                    @elseif($item->checkSPP($month, $year)->status == 'keluar')
                                                        <span class="text-danger">Siswa Keluar</span>
                                                    @else
                                                        <button class="btn btn-warning" type="submit"
                                                            form="order-form-{{ $item->id }}">Submit</button>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="d-flex justify-content-start">
                                                    @if ($orderAll->where('siswa_id', $item->id)->first())
                                                        <span>Sudah di Submit</span>
                                                    @elseif(!$item->checkSPP($month, $year) || $item->checkSPP($month, $year)->verified == 'no')
                                                        <span class="text-danger">Pembayaran Belum disetujui
                                                            Administrator</span>
                                                    @elseif($item->checkSPP($month, $year)->status == 'keluar')
                                                        <span class="text-danger">Siswa Keluar</span>
                                                    @else
                                                        <span>Belum disubmit</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                            @endforeach 


                            {{ $siswa->appends(request()->except('page'))->links() }}
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>



    <!-- Pay confirm Modal -->
    <div class="modal fade" id="pay-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('konfirmasi-spp') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Konfirmasi Pembayaran</label>
                            <select name="status" class="form-control">
                                <option value="">-- PILIH STATUS --</option>
                                <option value="yes">Pembayaran Sudah Masuk dan Terverifikasi</option>
                                <option value="no">Pembayaran Belum Terkonfirmasi</option>
                            </select>
                        </div>
                        <input type="hidden" name="id" id="id-siswa-pay-confirm">
                        <input type="hidden" name="month" value="{{ $month }}">
                        <input type="hidden" name="tahun" value="{{ $year }}">
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

    <!-- Input Pembayaran Modal -->
    <div class="modal fade" id="input-pembayaran-modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('input-spp') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Status Siswa</label>
                            <select name="status" class="form-control">
                                <option value="">-- PILIH STATUS --</option>
                                <option value="aktif">Aktif</option>
                                <option value="cuti">Cuti</option>
                                <option value="keluar">Keluar</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Pembayaran</label>
                            <input type="date" name="tanggal" class="form-control">
                            <span>Kosongkan apabila siswa keluar</span>
                        </div>
                        <input type="hidden" name="id" id="id-siswa-input-pembayaran">
                        <input type="hidden" name="month" value="{{ $month }}">
                        <input type="hidden" name="tahun" value="{{ $year }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Input Pembayaran Modal -->
    <!-- Insert Note Modal -->
    <div class="modal fade" id="insert-note" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="insert-note-title" id="exampleModalLabel">Modal Title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('insert-additional') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Biaya </label>
                            <input type="number" name="biaya" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="required">Tambah Note </label>
                            <textarea name="status" id="" cols="70" class="form-control" rows="5"></textarea>
                        </div>
                        <input type="hidden" name="id" id="id-siswa-insert-note">
                        <input type="hidden" name="month" value="{{ $month }}">
                        <input type="hidden" name="tahun" value="{{ $year }}">
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


    <div class="modal fade " id="rekap-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Rekap Order {{ $month }}-{{ $year }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" id="rekap-modul">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Modul</th>
                                <th>Permintaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderCounts as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->modul->nama ?? '--' }}</td>
                                    <td>{{ $item->count }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <table class="table table-bordered d-none" id="rekap-unit">
                        <thead>
                            <tr>
                                <th>Unit</th>
                                <th>Kelas</th>
                                <th>Kategori</th>
                                <th>Modul</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $prevUnit = null;
                                $prevKelas = null;
                                $prevKat = null;
                                $prevMod = null;
                                $unitRowspan = 0;
                            @endphp

                            @foreach ($ordersByUnit as $unitName => $ordersByKelas)
                                @foreach ($ordersByKelas as $kelasName => $ordersByKat)
                                    @foreach ($ordersByKat as $kat => $modul)
                                        @foreach ($modul as $mod_id => $count)
                                            <tr>
                                                <td {!! $unitName === $prevUnit ? "style='border-top:solid 2px #fff'" : '' !!}>
                                                    <b>{{ $unitName !== $prevUnit ? $unitName : '' }}</b>
                                                </td>
                                                <td {!! $unitName . $kelasName === $prevKelas ? "style='border-top:solid 2px #fff'" : '' !!}>
                                                    <b>{{ $unitName . $kelasName !== $prevKelas ? $kelasName : '' }}</b>
                                                </td>
                                                {{-- {!! $unitName.$kelasName !== $prevKelas ? '<td rowspan=' . ($ordersByKelas[$kelasName]->count()) . '>'. $kelasName .'</td>' : '' !!} --}}
                                                <td {!! $unitName . $kelasName . $kat === $prevKat ? "style='border-top:solid 2px #fff'" : '' !!}>
                                                    <b>{{ $unitName . $kelasName . $kat !== $prevKat ? $kat : '' }}</b>
                                                </td>
                                                {{-- <td>{{ $kat ?? '-- ' }}</td> --}}
                                                <td>{{ $moduls->where('id', $mod_id)->first()->nama ?? '-- ' }}</td>
                                                <td>{{ $count }}</td>

                                                <td {!! $unitName . $kelasName . $kat === $prevKat ? "style='border-top:solid 2px #fff'" : '' !!}>
                                                    <b>{{ $unitName . $kelasName . $kat !== $prevKat ? $modul->sum() : '' }}</b>
                                                </td>

                                            </tr>

                                            @php
                                                $prevUnit = $unitName;
                                                $prevKelas = $unitName . $kelasName;
                                                $prevKat = $unitName . $kelasName . $kat;
                                                $prevMod = $unitName . $kelasName . $kat . $mod_id;
                                            @endphp
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" id="print2">Print</button>
                    <button class="btn btn-primary" id="button-rekap-modul">Unit View</button>
                    <button class="btn btn-primary d-none" id="button-rekap-unit">Modul View</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

    <!-- Form Change Unit -->
    <form class="ml-3" action="{{ route('order.index') }}" method="get" id="unit-form">
        @csrf
    </form>
    <!-- /Form Change Unit -->
    <!-- Form Change Kelas -->
    <form class="ml-3" action="{{ route('order.index') }}" method="get" id="kelas-form">
        @csrf
    </form>
    <!-- /Form Change Unit -->

@endsection

@section('script')
    <script>
        $('#change-tahun').on('change', function() {
            var tahun = $(this).val();
            var month = $(this).data('month');
            var csrfToken = "{{ csrf_token() }}"

            $.ajax({
                url: "{{ route('order.index') }}",
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
    <script>
        $('.insert-note').on('click', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');

            $('.insert-note-title').text('Tambah Note - ' + nama);
            $('#id-siswa-insert-note').val(id);
            $('#insert-note').modal('show');
        });
    </script>

    <script>
        $('.change-level').on('change', function() {
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

    <script>
        $('#rekap-toggle').on('click', function() {
            $('#rekap-modal').modal('show');
        })
    </script>

    <script>
        $('#button-rekap-modul').on('click', function() {
            $('#button-rekap-modul').addClass('d-none');
            $('#button-rekap-unit').removeClass('d-none');
            $('#rekap-unit').removeClass('d-none');
            $('#rekap-modul').addClass('d-none');
        })

        $('#button-rekap-unit').on('click', function() {
            $('#button-rekap-unit').addClass('d-none');
            $('#button-rekap-modul').removeClass('d-none');
            $('#rekap-modul').removeClass('d-none');
            $('#rekap-unit').addClass('d-none');
        })
    </script>

    <script>
        $('.pay-confirm').on('click', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            console.log(id);
            $('.modal-title').text('Verifikasi Pembayaran - ' + nama);
            $('#id-siswa-pay-confirm').val(id);
            $('#pay-confirm-modal').modal('show');
        });
    </script>

    <script>
        $('.input-pembayaran').on('click', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');

            $('.modal-title').text('Input Tanggal Pembayaran - ' + nama);
            $('#id-siswa-input-pembayaran').val(id);
            $('#input-pembayaran-modal').modal('show');
        });
    </script>
    <script src="{{ asset('printThis-master/printThis.js') }}"></script>
    <script>
        $('#print').on('click', function() {
            $("#print-this").printThis();
        })

        $('#print2').on('click', function() {
            $("#rekap-unit").printThis();
        })

        $(document).ready(function() {
            // Check if the body has the specific-page class
            if ($('body').hasClass('pagination')) {
                // Hide the DataTables information element
                $('.pagination').css('display', 'none');
            }
        });
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
 