<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class PresensiController extends Controller
{
    public function create()
    {
        $hariini = date('Y-m-d');
        $nip = Auth::guard('dosen')->user()->nip;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nip', $nip)->count();
        return view('presensi.create', compact('cek'));
    }


    public function store(Request $request)
    {
        $nip = Auth::guard('dosen')->user()->nip;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lokasi = $request->lokasi;
        $keterangan = $request->keterangan;

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nip', $nip)->count();
        $ket = $cek > 0 ? "pulang" : "masuk";

        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nip . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        if ($ket === "pulang") {
            $data_pulang = [
                'jam_out' => $jam,
                'foto_out' => $fileName,
                'lokasi_out' => $lokasi,
                'keterangan_out' => $keterangan,
            ];
            $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nip', $nip)->update($data_pulang);
            if ($update) {
                Storage::put($file, $image_base64);
                return response("success|Absen Pulang Berhasil|out");
            } else {
                return response("error|Absen Gagal, Silahkan Coba Lagi|out");
            }
        } else {
            $data = [
                'nip' => $nip,
                'tgl_presensi' => $tgl_presensi,
                'jam_in' => $jam,
                'foto_in' => $fileName,
                'lokasi_in' => $lokasi,
                'keterangan_in' => $keterangan,
            ];
            $simpan = DB::table('presensi')->insert($data);
            if ($simpan) {
                Storage::put($file, $image_base64);
                return response("success|Absen Masuk Berhasil|in");
            } else {
                return response("error|Absen Gagal, Silahkan Coba Lagi|in");
            }
        }
    }

    public function editprofil()
    {
        $nip = Auth::guard('dosen')->user()->nip;
        $dosen = DB::table('dosen')->where('nip', $nip)->first();

        return view('presensi.editprofil', compact('dosen'));
    }

    public function updateprofil(Request $request)
    {
        $nip = Auth::guard('dosen')->user()->nip;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $dosen = DB::table('dosen')->where('nip', $nip)->first();

        if ($request->hasFile('foto')) {
            $foto = $nip . "." . $request->file('foto')->getClientOriginalExtension();
            $folderPath = "public/uploads/dosen/";
            $request->file('foto')->storeAs($folderPath, $foto);
        } else {
            $foto = $dosen->foto;
        }

        if (empty($request->password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto,
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }

        $update = DB::table('dosen')->where('nip', $nip)->update($data);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/dosen/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            if (!$nip) {

                return Redirect::back()->with(['error' => 'Data Gagal Di Update']);
            } else {
                return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
            }
        }
    }

    public function histori()
    {
        $namabulan = [
            "",
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "Novemver",
            "Desember"
        ];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nip = Auth::guard('dosen')->user()->nip;

        $histori = DB::table('presensi')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->where('nip', $nip)
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.gethistori', compact('histori'));
    }

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

    public function halamanUpload()
    {
        $hariini = date('Y-m-d');
        $nip = Auth::guard('dosen')->user()->nip;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nip', $nip)->count();

        return view('presensi.uploadabsensi');
    }
    
    function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $R * $c;
        return $distance;
    }

    public function uploadabsensi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required|string',
            'metadata' => 'nullable|string',
        ], [
            'foto.required' => 'Foto wajib diunggah.',
            'foto.string' => 'Foto harus berupa string base64.',
            'metadata.string' => 'Metadata harus berupa string.',
        ]);

        if ($validator->fails()) {
            return redirect('/presensi/halamanupload')
                ->withErrors($validator)
                ->withInput();
        }

        $metadata = $request->metadata ? json_decode($request->metadata, true) : [];

        if (!isset($metadata['gpsLatitude']) || !isset($metadata['gpsLongitude'])) {
            return redirect('/presensi/halamanupload')
                ->with('error', 'Gambar tidak memilik data lokasi.')
                ->withInput();
        }

        $nip = Auth::guard('dosen')->user()->nip;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lokasi = $request->lokasi;

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nip', $nip)->count();
        $ket = ($cek > 0) ? "pulang" : "masuk";

        $image = $request->foto;
        $image_parts = explode(";base64,", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $formatName = $nip . "-" . $tgl_presensi . "-" . $ket;
        $fileName = $formatName . ".png";
        $folderPath = "public/uploads/absensi/";
        $file = $folderPath . $fileName;

        $gpsLatitude = $metadata['gpsLatitude'];
        $gpsLongitude = $metadata['gpsLongitude'];
        $location = $gpsLatitude . ',' . $gpsLongitude;

        $officeLat = 0.476364;
        $officeLon = 101.383426;

        $distance = $this->calculateDistance($gpsLatitude, $gpsLongitude, $officeLat, $officeLon);

        $keterangan = $distance <= 1 ? "Dalam UNRI" : "Luar UNRI";

        if ($cek > 0) {
            $data_pulang = [
                'jam_out' => $metadata['dateTimeOriginal'] ?? $jam,
                'foto_out' => $fileName,
                'lokasi_out' => $location,
                'keterangan_out' => $keterangan,
            ];
            $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nip', $nip)->update($data_pulang);
            if ($update) {
                Storage::put($file, $image_base64);
                return redirect('/dashboard')->with('success', 'Absen Pulang Berhasil');
            } else {
                return redirect('/dashboard')->with('error', 'Absen Gagal, Silahkan Coba Lagi');
            }
        } else {
            if ($ket === "pulang") {
                return redirect('/dashboard')->with('error', 'Anda belum absen masuk hari ini.');
            }

            $data = [
                'nip' => $nip,
                'tgl_presensi' => $tgl_presensi,
                'jam_in' => $metadata['dateTimeOriginal'] ?? $jam,
                'foto_in' => $fileName,
                'lokasi_in' => $location,
                'keterangan_in' => $keterangan,
            ];

            $simpan = DB::table('presensi')->insert($data);
            if ($simpan) {
                Storage::put($file, $image_base64);
                return redirect('/dashboard')->with('success', 'Absen Masuk Berhasil');
            } else {
                return redirect('/dashboard')->with('error', 'Absen Gagal, Silahkan Coba Lagi');
            }
        }
    }


    public function getMetadata($filePath)
    {
        $metadata = [
            'tgl_presensi' => null,
            'jam' => null,
            'lokasi' => null,
        ];

        if ($exifData = @exif_read_data($filePath)) {
            if (isset($exifData['DateTimeOriginal'])) {
                $dateTime = new DateTime($exifData['DateTimeOriginal']);
                $metadata['tgl_presensi'] = $dateTime->format('Y-m-d');
                $metadata['jam'] = $dateTime->format('H:i:s');
            }

            if (isset($exifData['GPSLatitude']) && isset($exifData['GPSLongitude'])) {
                $metadata['lokasi'] = [
                    'latitude' => $this->getGps($exifData['GPSLatitude'], $exifData['GPSLatitudeRef']),
                    'longitude' => $this->getGps($exifData['GPSLongitude'], $exifData['GPSLongitudeRef']),
                ];
            }
        }

        return $metadata;
    }

    private function getGps($exifCoord, $hemi)
    {
        $degrees = count($exifCoord) > 0 ? $this->gps2Num($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? $this->gps2Num($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? $this->gps2Num($exifCoord[2]) : 0;

        $flip = ($hemi == 'W' || $hemi == 'S') ? -1 : 1;

        return $flip * ($degrees + ($minutes / 60) + ($seconds / 3600));
    }

    private function gps2Num($coordPart)
    {
        $parts = explode('/', $coordPart);

        if (count($parts) <= 0) {
            return 0;
        }

        if (count($parts) == 1) {
            return $parts[0];
        }

        return floatval($parts[0]) / floatval($parts[1]);
    }
}
