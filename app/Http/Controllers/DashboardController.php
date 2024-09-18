<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1;
        $tahunini = date("Y");
        $nip = Auth::guard('dosen')->user()->nip;

        $presensihariini = DB::table('presensi')
            ->where('nip', $nip)
            ->where('tgl_presensi', $hariini)
            ->latest('jam_in')
            ->first();

        $historibulanini = DB::table('presensi')
            ->where('nip', $nip)
            ->whereMonth('tgl_presensi', $bulanini)
            ->whereYear('tgl_presensi', $tahunini)
            ->orderBy('tgl_presensi')
            ->get();

        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nip) as jmlhadir, SUM(IF(TIME(jam_in) > "07:30", 1, 0)) as jmlterlambat')
            ->where('nip', $nip)
            ->whereMonth('tgl_presensi', $bulanini)
            ->whereYear('tgl_presensi', $tahunini)
            ->first();

        $leaderboard = DB::table('presensi')
            ->join('dosen', 'presensi.nip', '=', 'dosen.nip')
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in')
            ->get();

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $rekapizin = DB::table('pengajuan')
            ->selectRaw('SUM(IF(status="i", 1,0)) as jmlizin, SUM(IF(status="s", 1,0)) as jmlsakit')
            ->where('nip', $nip)
            ->whereMonth('tgl_izin', $bulanini)
            ->whereYear('tgl_izin', $tahunini)
            ->where('status_approved', 1)
            ->first();

        return view('dashboard.dashboard', compact('presensihariini', 'historibulanini', 'namabulan', 'bulanini', 'tahunini', 'rekappresensi', 'leaderboard', 'rekapizin'));
    }
}
