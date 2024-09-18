<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function izin()
    {
        $nip = Auth::guard('dosen')->user()->nip;
        $dataizin = DB::table('pengajuan')->where('nip', $nip)->get();
        return view('presensi.izin', compact('dataizin'));
    }

    public function pengajuanizin()
    {
        return view('presensi.pengajuanizin');
    }

    public function storeizin(Request $request)
    {
    // Validasi data
    $request->validate([
        'tgl_izin' => 'required|date',
        'status' => 'required',
        'keterangan' => 'required',
        'bukti_izin' => 'required|image|mimes:jpeg,png,jpg|max:10048', // Membatasi jenis file dan ukuran
    ]);

    // Mendapatkan NIP dosen yang sedang login
    $nip = Auth::guard('dosen')->user()->nip;

    // Mengambil data dari form
    $tgl_izin = $request->tgl_izin;
    $status = $request->status;
    $keterangan = $request->keterangan;
    $nama_lengkap = Auth::guard('dosen')->user()->nip;
    

    // Memproses upload bukti izin (gambar)
    if ($request->hasFile('bukti_izin')) {
        // Menghasilkan nama untuk file
        $file = $request->file('bukti_izin');
        $formatName = $nama_lengkap . "-" . $status;
        $fileName = $formatName . ".png";
        // $fileName = $nama_lengkap . time() . '_' . 'izin';
        
        // Menyimpan file ke folder public/storage/bukti_izin
        $file->move(public_path('storage/bukti_izin'), $fileName);
    }

    // Data yang akan disimpan ke database
    $data = [
        'nip' => $nip,
        'tgl_izin' => $tgl_izin,
        'status' => $status,
        'keterangan' => $keterangan,
        'bukti_izin' => $fileName // Simpan nama file dalam database
    ];

    // Insert data ke tabel 'pengajuan'
    $simpan = DB::table('pengajuan')->insert($data);

    // Cek apakah penyimpanan berhasil
    if ($simpan) {
        return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
    } else {
        return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
    }
    }
}
