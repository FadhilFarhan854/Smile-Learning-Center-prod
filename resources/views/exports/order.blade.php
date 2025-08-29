<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Kelas</th>
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
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($siswa as $item)
        <tr>
            <td>{{$item->kelas->nama}}</td>
            <td>{{$item->nama}}</td>
            <td>
                @if (optional($item->checkSPP($month, $year))->verified == 'yes')
                <span class="{{$item->checkStatus($item->checkSPP($month, $year)->status)}}">{{ucwords($item->checkSPP($month, $year)->status)}}</span>
                @else
                <span >Menunggu konfirmasi</span>
                @endif
            </td>
            <td>
              

                @if (count($item->spp->where('bulan', $month)->where('tahun', $year)) > 0 && $item->checkSPP($month, $year)->verified == 'yes')
                <span>{{$item->spp->where('bulan', $month)->where('tahun', $year)->first()->tanggal != null ? formattedDate($item->spp->where('bulan', $month)->where('tahun', $year)->first()->tanggal) : ''}}</span>
                @else
                <span class="text-danger">Belum dikonfirmasi</span>
                @endif

               
            </td>
            <td>
          

                @if (count($item->additional->where('bulan', $month)->where('tahun', $year)))
                <span>{{$item->additional->where('bulan', $month)->where('tahun', $year)->first()->status }}</span>
                @else
                <span class="text-danger">Belum dikonfirmasi</span>
                @endif

            
            </td>
            <td class="td-min">
                @if ($item->checkOrder($month, $year))
                {{$item->checkOrder($month, $year)->level}}
                @else
                --
                @endif
            </td>
            <td class="td-min">
                @if ($item->checkOrderSpec($month, $year, 'baca'))
                {{$item->checkOrderSpec($month, $year, 'baca')->modul->nama ?? '--'}}
                
                @endif
            </td>
            <td class="td-min">
                @if ($item->checkOrderSpec($month, $year, 'tulis'))
                {{$item->checkOrderSpec($month, $year, 'tulis')->modul->nama ?? '--'}}
               
                @endif
                
            </td>
            <td class="td-min">
                @if ($item->checkOrderSpec($month, $year, 'hitung'))
                {{$item->checkOrderSpec($month, $year, 'hitung')->modul->nama ?? '--'}}
               
                @endif
            </td>
            <td class="td-min">
                @if ($item->checkOrderSpec($month, $year, 'modul SD'))
                {{$item->checkOrderSpec($month, $year, 'modul SD')->modul->nama ?? '--'}}
               
                @endif
            </td>
            <td class="td-min">
                @if ($item->checkOrderSpec($month, $year, 'english'))
                {{$item->checkOrderSpec($month, $year, 'english')->modul->nama ?? '--'}}
               
                @endif
            </td>
            <td class="td-min">
                @if ($item->checkOrderSpec($month, $year, 'iqro'))
                {{$item->checkOrderSpec($month, $year, 'iqro')->modul->nama ?? '--'}}
              
                @endif
            </td>
            <td class="td-min">
                @if ($item->checkOrderSpec($month, $year, 'daftar'))
                {{$item->checkOrderSpec($month, $year, 'daftar')->modul->nama ?? '--'}}
              
                @endif
            </td>
            <td class="td-min">
                @if ($item->checkOrderSpec($month, $year, 'lain'))
                {{$item->checkOrderSpec($month, $year, 'lain')->modul->nama ?? '--'}}
               
                @endif
            </td>
            <td>
               
                <div class="d-flex justify-content-start">
                    @if ($item->checkOrder($month, $year))
                    <span>Sudah di Submit</span> 
                    @elseif(!$item->checkSPP($month, $year) || $item->checkSPP($month, $year)->verified == 'no')
                    <span class="text-danger">Pembayaran Belum disetujui Administrator</span>
                    @elseif($item->checkSPP($month, $year)->status == 'keluar')
                    <span class="text-danger">Siswa Keluar</span>
                    @else
                    <span>Belum disubmit</span>
                    @endif
                </div>
               
            </td>
        </tr>
        @endforeach
    </tbody>
</table>