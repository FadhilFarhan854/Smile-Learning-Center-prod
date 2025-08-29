<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Jumlah Kelas</th>
   
        </tr>
    </thead>
    <tbody>
        @foreach ($unit as $item)
        <tr>
            <td>{{$item->nama}}</td>
            <td>{{$item->jumlahKelas()}}</td>

        </tr>
        @endforeach
    </tbody>
</table>