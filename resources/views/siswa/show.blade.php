@extends('layout.main')

@section('content')

<div class="container-fluid">
    
    <!-- Page Heading -->
    @if (session('status'))
    <div class="alert alert-success">
        {{session('status')}}
    </div>
    @endif
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h3>Profil Siswa</h3>
        </div>
        <div class="card-body px-5">
            <div class="row">
                <h5>{{ucwords($siswa->nama)}}</h5>
            </div>
            <div class="row mt-4">
                <div class="col-lg-6 p-1">
                    <span class="font-weight-bold">Data Pribadi</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 p-0">
                    <table class="table ">
                        <tbody>
                            <tr >
                                <td class="td-max">NIM</td>
                                <td class="td-max">{{$siswa->nim}}</td>
                            </tr>
                            <tr>
                                <td class="td-max">Tanggal Masuk</td>
                                <td class="td-max">{{formattedDate($siswa->tanggal_masuk)}}</td>
                            </tr>
                            <tr>
                                <td class="td-max">Tempat, Tanggal Lahir</td>
                                <td class="td-max">{{$siswa->tempat_lahir}}, {{formattedDate($siswa->tanggal_lahir)}}</td>
                            </tr>
                            <tr>
                                <td class="td-max">Nama Ayah</td>
                                <td class="td-max">{{ucwords($siswa->nama_ayah)}}</td>
                            </tr>
                            <tr>
                                <td class="td-max">Nama Ibu</td>
                                <td class="td-max">{{ucwords($siswa->nama_ibu)}}</td>
                            </tr>
                            <tr>
                                <td class="td-max">Telp/HP Wali</td>
                                <td class="td-max">{{$siswa->no_wali_1 ?? '--'}}</td>
                            </tr>
                            <tr>
                                <td class="td-max"></td>
                                <td class="td-max">{{$siswa->no_wali_2 ?? '--'}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-6 p-1">
                    <span class="font-weight-bold">Data Studi</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 p-0">
                    <table class="table">
                        <tbody>
                            <tr >
                                <td class="td-max">Level</td>
                                <td class="td-max">{{$siswa->level}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">Modul Baca</td>
                                <td class="td-max">{{$siswa->latestmodul('baca')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">Modul Tulis</td>
                                <td class="td-max">{{$siswa->latestmodul('tulis')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">Modul Hitung</td>
                                <td class="td-max">{{$siswa->latestmodul('hitung')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">Modul SD</td>
                                <td class="td-max">{{$siswa->latestmodul('modul SD')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">Modul English</td>
                                <td class="td-max">{{$siswa->latestmodul('english')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">Iqro</td>
                                <td class="td-max">{{$siswa->latestmodul('iqro')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">Set Pendaftaran</td>
                                <td class="td-max">{{$siswa->latestmodul('daftar')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">Modul Lainnya</td>
                                <td class="td-max">{{$siswa->latestmodul('lain')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">English Verbal</td>
                                <td class="td-max">{{$siswa->latestmodul('verbal')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">Sempoa Intro</td>
                                <td class="td-max">{{$siswa->latestmodul('sempoa')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">IQ Meet</td>
                                <td class="td-max">{{$siswa->latestmodul('iq')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">Aritmatika</td>
                                <td class="td-max">{{$siswa->latestmodul('aritmatika')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">Anak Juara</td>
                                <td class="td-max">{{$siswa->latestmodul('juara')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">Modul Orangtua</td>
                                <td class="td-max">{{$siswa->latestmodul('ortu')->nama ?? '--'}}</td>
                            </tr>
                            <tr >
                                <td class="td-max">Cryon</td>
                                <td class="td-max">{{$siswa->latestmodul('cryon')->nama ?? '--'}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</div>

@endsection

