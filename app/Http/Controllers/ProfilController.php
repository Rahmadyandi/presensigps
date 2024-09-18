<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ProfilController extends Controller
{
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
}
