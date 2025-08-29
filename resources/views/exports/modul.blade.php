<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Level</th>
            <th>Kategori</th>

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
   
            <td class="d-flex justify-content-around">
 
                {{$item->countStock()}} 
        
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