<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class SiswaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //dd($row['kelas']);
        $kelas = Kelas::where('nama', $row['kelas'])->first();

        return new Siswa([
            'kelas_id' => $kelas->id,
            'nim' => str_pad($row['nim'], 5, 0, STR_PAD_LEFT),
            'nama'=> $row['nama_murid'],
            'tanggal_lahir' => Carbon::instance(Date::excelToDateTimeObject($row['tanggal_lahir'])),
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_masuk' => Carbon::instance(Date::excelToDateTimeObject($row['tangga_masuk'])),
            'nama_ayah' => $row['nama_ayah'],
            'nama_ibu' => $row['nama_ibu'],
            'status' => 'aktif',
            'tanggal_pembayaran' => Carbon::instance(Date::excelToDateTimeObject($row['tanggal_pembayaran'])),
            'no_wali_1' => $row['telepon']
        ]);
    }
}
