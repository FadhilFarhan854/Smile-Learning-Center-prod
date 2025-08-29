<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
			<th>Nama Guru</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>TTL</th>
            <th>Nama Ayah</th>
        <th>Nama Ibu</th>
            <th>No HP</th>
            <th>Tgl Masuk</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswa as $item)
            <tr>
				<td>
                    <span class="{{ optional($item->kelas->guru)->status === 'non-aktif' ? 'text-danger' : '' }}">
                        {{ optional($item->kelas->guru)->name ?? '--' }}
                    </span> / 
                    <span class="{{ optional($item->kelas->user)->status === 'non-aktif' ? 'text-danger' : '' }}">
                        {{ optional($item->kelas->user)->name ?? '--' }}
                    </span>
                </td>
                <td>{{$item->nim}}</td>
                <td><a href="{{route('siswa.show', $item->id)}}">{{$item->nama}}</a></td>
                <td>{{$item->tempat_lahir}}, {{$item->tanggal_lahir}}</td>
				<td>{{$item->nama_ayah}}</td>
				<td>{{$item->nama_ibu}}</td>
				<td>{{$item->no_wali_1}}</td>
				<td>{{$item->tanggal_masuk}}</td>        
                <td>          
                    <span>{{ucwords($item->status)}}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>