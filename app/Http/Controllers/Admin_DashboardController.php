<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class Admin_DashboardController extends Controller
{
    public function index(Request $request) 
    {
        $id_user = $request->session()->get('id_user');
        $dosen = Dosen::where('id_user', $id_user)->first();

        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'role' => $request->session()->get('role'),
            'dosen' => $dosen,
            'jumlahDosen' => Dosen::count(),
            'MenungguPersetujuan' => Pengajuan::where('status_approved', '0')->count(),
            'IzinDiterima' => Pengajuan::where('status_approved', '1')->count(),
            'IzinDitolak' => Pengajuan::where('status_approved', '2')->count(),
        ]);
    }
}
