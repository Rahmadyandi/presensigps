<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Presensi extends Model
{
    use HasFactory;
    protected $table ='presensi';

    public function getNamaDosen()
    {
        return DB::table('presensi')
        ->join('dosen', 'dosen.nip', '=', 'presensi.nip')
        ->select('dosen.nama_lengkap')
        ->get();
    }
}
