@extends('layout.main')

@section('content')

<div class="container-fluid">
    
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Order</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="col-lg-6 offset-lg-2">
                <form action="{{route('order.update', $order->id)}}" method="post">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label class="required">Level</label>
						@if (auth()->user()->role != 'administrator 2')     
						<select name="level" required class="form-control">
                            <option value="">-- PILIH --</option>
                            @for ($i = 1; $i < 10; $i++)
                            <option value="{{$i}}" {{$order->level == $i ? 'selected' : ''}}>{{$i}}</option>
                            @endfor
                        </select>
						@else
                        <select name="level" required class="form-control">
                            <option value="">-- PILIH --</option>
                            @for ($i = 1; $i < 10; $i++)
                            <option value="{{$i}}" {{$i < $order->level ? 'disabled' : ''}} {{$order->level == $i ? 'selected' : ''}}>{{$i}}</option>
                            @endfor
                        </select>
						@endif
                    </div>
                    
                    <div class="form-group">
                        <label >Modul Baca</label>
                        <select class="form-control" id="modul-baca">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModul('baca', $modulAll) as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$baca->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="modul_baca" id="input-baca" value="{{$baca->modul_id ?? null}}">
                        @if (session('error-baca-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-baca-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>
                    
                    
                    <div class="form-group">
                        <label >Modul Tulis</label>
                        <select  class="form-control" id="modul-tulis">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('tulis') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$tulis->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="modul_tulis" id="input-tulis" value="{{$tulis->modul_id ?? null}}">
                        @if (session('error-tulis-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-tulis-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label>Modul Hitung</label>
                        <select class="form-control" id="modul-hitung">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('hitung') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$hitung->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="modul_hitung" id="input-hitung" value="{{$hitung->modul_id ?? null}}">
                        @if (session('error-hitung-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-hitung-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label >Modul SD</label>
                        <select class="form-control" id="modul-sd">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('modul SD') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$modul_sd->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="modul_sd" id="input-sd" value="{{$modul_sd->modul_id ?? null}}">
                        @if (session('error-sd-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-sd-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label >Modul English</label>
                        <select class="form-control" id="modul-english">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('english') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$english->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="english" id="input-english" value="{{$english->modul_id ?? null}}">
                        @if (session('error-english-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-english-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label>Iqro</label>
                        <select class="form-control" id="modul-iqro">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('iqro') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$iqro->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="iqro" id="input-iqro" value="{{$iqro->modul_id ?? null}}">
                        @if (session('error-iqro-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-iqro-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Daftar</label>
                        <select class="form-control" id="modul-daftar">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('daftar') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$daftar?->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="daftar" id="input-daftar" value="{{$daftar?->modul_id ?? null}}">
                        @if (session('error-daftar-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-daftar-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label >Modul Lain</label>
                        <select class="form-control" id="modul-lain">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('lain') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$lain->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="lain" id="input-lain" value="{{$lain->modul_id ?? null}}">
                        @if (session('error-lain-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-lain-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label >English Verbal</label>
                        <select class="form-control" id="modul-verbal">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('verbal') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$verbal->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="verbal" id="input-verbal" value="{{$verbal->modul_id ?? null}}">
                        @if (session('error-verbal-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-verbal-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label >Sempoa</label>
                        <select class="form-control" id="modul-sempoa">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('sempoa') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$sempoa->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="sempoa" id="input-sempoa" value="{{$sempoa->modul_id ?? null}}">
                        @if (session('error-sempoa-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-sempoa-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label >IQ</label>
                        <select class="form-control" id="modul-iq">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('iq') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$iq->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="iq" id="input-iq" value="{{$iq->modul_id ?? null}}">
                        @if (session('error-iq-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-iq-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label >Aritmatika</label>
                        <select class="form-control" id="modul-aritmatika">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('aritmatika') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$aritmatika->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="aritmatika" id="input-aritmatika" value="{{$aritmatika->modul_id ?? null}}">
                        @if (session('error-aritmatika-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-aritmatika-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label >Juara</label>
                        <select class="form-control" id="modul-juara">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('juara') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$juara->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="juara" id="input-juara" value="{{$juara->modul_id ?? null}}">
                        @if (session('error-juara-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-juara-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label >Ortu</label>
                        <select class="form-control" id="modul-ortu">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('ortu') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$ortu->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="ortu" id="input-ortu" value="{{$ortu->modul_id ?? null}}">
                        @if (session('error-ortu-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-ortu-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label >Cryon</label>
                        <select class="form-control" id="modul-cryon">
                            <option value="">--</option>
                            @foreach ($order->siswa->chooseModulAll('cryon') as $md)
                            <option value="{{$md->id}}" style="{{$md->countStock() <= 0 ?'display:none' : ''}}" {{$cryon->modul_id == $md->id ? 'selected' : ''}}>Level {{$md->level}} : {{$md->nama}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="cryon" id="input-cryon" value="{{$cryon->modul_id ?? null}}">
                        @if (session('error-cryon-'.$order->siswa_id))
                        <div class="alert alert-danger p-1">
                            {{session('error-cryon-'.$order->siswa_id)}}
                        </div>
                        @endif
                    </div>
                    <input type="hidden" name="siswa" value="{{$order->siswa_id}}">
                    <input type="hidden" name="month" value="{{$order->bulan}}">
                    <input type="hidden" name="tahun" value="{{$order->tahun}}">
                    <input type="hidden" name="status" value="{{$order->status}}">
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

@section('script')
    <script>
        $('#modul-baca').on('change', function(){
            var modul = $(this).val();

            $('#input-baca').val(modul);
        });

        $('#modul-tulis').on('change', function(){
            var modul = $(this).val();

            $('#input-tulis').val(modul);
        });

        $('#modul-hitung').on('change', function(){
            var modul = $(this).val();

            $('#input-hitung').val(modul);
        });

        $('#modul-sd').on('change', function(){
            var modul = $(this).val();

            $('#input-sd').val(modul);
        });

        $('#modul-english').on('change', function(){
            var modul = $(this).val();

            $('#input-english').val(modul);
        });

        $('#modul-iqro').on('change', function(){
            var modul = $(this).val();

            $('#input-iqro').val(modul);
        });

        $('#modul-daftar').on('change', function(){
            var modul = $(this).val();

            $('#input-daftar').val(modul);
        });

        $('#modul-lain').on('change', function(){
            var modul = $(this).val();

            $('#input-lain').val(modul);
        });

        $('#modul-verbal').on('change', function(){
            var modul = $(this).val();

            $('#input-verbal').val(modul);
        });

        $('#modul-sempoa').on('change', function(){
            var modul = $(this).val();

            $('#input-sempoa').val(modul);
        });

        $('#modul-iq').on('change', function(){
            var modul = $(this).val();

            $('#input-iq').val(modul);
        });

        $('#modul-aritmatika').on('change', function(){
            var modul = $(this).val();

            $('#input-aritmatika').val(modul);
        });

        $('#modul-juara').on('change', function(){
            var modul = $(this).val();

            $('#input-juara').val(modul);
        });

        $('#modul-ortu').on('change', function(){
            var modul = $(this).val();

            $('#input-ortu').val(modul);
        });

        $('#modul-cryon').on('change', function(){
            var modul = $(this).val();

            $('#input-cryon').val(modul);
        });
    </script>
@endsection