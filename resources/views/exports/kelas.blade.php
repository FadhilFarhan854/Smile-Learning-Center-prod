<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Unit</th>
            <th>Nama</th>
            <th>Admin</th>
            <th>Guru</th>
            <th>Jumlah Siswa</th>
       
        </tr>
    </thead>

    <tbody>
        @foreach ($kelas as $item)
        <tr>
            <td>{{$item->unit->nama}}</td>
            <td>{{$item->nama}}</td>
            <td id="admin-{{$item->id}}">
   
                <span class="{{isset($item->user->status) && $item->user->status === 'non-aktif' ? 'text-danger' : ''}}">{{$item->user->name ?? '--'}}</span>
              
            </td>
            <td id="guru-{{$item->id}}">

                <span class="{{isset($item->guru->status) && $item->guru->status === 'non-aktif' ? 'text-danger' : ''}}">{{$item->guru->name ?? '--'}}</span>
               
            </td>
            <td>{{$item->jumlahSiswa()}}</td>

        </tr>
        @endforeach
    </tbody>
</table>