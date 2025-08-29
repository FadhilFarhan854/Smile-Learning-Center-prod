<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>NIK</th>
            <th>No. Rekening</th>
            <th>Tanggal Masuk</th>
    
            
        </tr>
    </thead>
    <tbody>
        @foreach ($user as $item)
        <tr>
            <td>{{$item->name}}</td>
            <td>{{$item->email}}</td>
            <td>{{ucwords($item->role)}}</td>
            <td>{{$item->nik}}</td>
            <td>{{$item->rekening}}</td>
            <td>{{formattedDate($item->tanggal_masuk)}}</td>

        </tr>
        @endforeach
    </tbody>
</table>