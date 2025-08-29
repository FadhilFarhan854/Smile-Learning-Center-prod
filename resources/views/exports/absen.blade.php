<table class="table-absen "  width="100%" cellspacing="0">
    <thead class="mt-5"> 
        <tr>
            <th class="px-44 f-9 th-judul">Data Absen Siswa, Bulan {{$month}} Tahun {{$year}}</th>
            @for ($i = 1; $i <= $daysInMonth; $i++)
            <th class="px-44 f-9 th-absen">{{$i}} <br>{{ $weekMap[\Carbon\Carbon::parse(\Carbon\Carbon::parse($i . '-' . $month . '-' . $year))->dayOfWeek] }}</th>
            @endfor
            <th class="px-44 f-9 th-absen">Total <br> Hadir</th>
            <th class="px-44 f-9 th-absen">Ket</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($siswa as $item)
        <tr> 
            <td class="td-judul">{{$item->nama}}</td>
            @foreach ($dateToFill[$item->id] as $key=>$it)
        
            <td class="td-absen">
                @if ($it == 'PRE' || $it == 'POST')
                -
                @elseif($it == 'EMPTY')
                <button type="button" class="badge badge-light f-10 p-1 border " data-toggle="modal" data-target="input-absen-{{$item->id}}" data-id="{{ $item->id }}" data-date="{{ $key }}" data-month="{{$month}}" data-year="{{$year}}" data-nama="{{$item->nama}}"><i class="fa fa-plus text-secondary"></i></button>
                @elseif($it == 'masuk')
                Hadir
                @elseif($it == 'alfa')
                Alfa
                @elseif($it == 'cuti')
                Cuti
                @elseif($it == 'sakit')
                Sakit
                @elseif($it == 'izin')
                Izin
                @endif
            </td>
           
            @endforeach
            <td class="transparent-button f-13 p-1  input-absen">{{$item->countAbsen($month, $year)}}</td>
            <td class="transparent-button f-13 p-1  input-absen">
              @if ($item->checkAbsenNotes($month, $year) )
              <button type="button" class="badge badge-warning f-10 p-1  border edit-notes" data-id="{{ $item->id }}" data-month="{{$month}}" data-year="{{$year}}" data-nama="{{$item->nama}}"><i class="fas fa-sticky-note"></i> </button>
              @else
              <button type="button" class="badge badge-info f-10 p-1  border input-notes" data-id="{{ $item->id }}" data-month="{{$month}}" data-year="{{$year}}" data-nama="{{$item->nama}}"><i class="fa fa-plus"></i> </button>
              @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>