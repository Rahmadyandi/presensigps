<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Presensi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class Admin_PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id_user = $request->session()->get('id_user');
        $dosen = Dosen::where('id_user', $id_user)->first();
        
        $months = Pengajuan::selectRaw('DATE_FORMAT(tgl_izin, "%Y-%m") as month')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        $selectedMonth = $request->input('month', $months->first()->month ?? null);

        $pengajuan = Pengajuan::join('dosen', 'dosen.nip', '=', 'pengajuan.nip')
        ->select('pengajuan.*', 'dosen.nama_lengkap') // Pilih kolom yang ingin ditampilkan
        ->whereRaw('DATE_FORMAT(tgl_izin, "%Y-%m") = ?', [$selectedMonth])
        ->get();

        return view('admin.pengajuan', [
            'title' => 'Pengajuan',
            'active' => 'pengajuan',
            'role' => $request->session()->get('role'),
            'pengajuan' => $pengajuan,
            'dosen' => $dosen,
            'months' => $months,
            'selectedMonth' => $selectedMonth,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function terima(Request $request, $id)
    {
        $pengajuan = Pengajuan::find($id);
        if ($pengajuan) {
            $pengajuan->status_approved = '1';
            $pengajuan->save();

            return redirect()->back()->with('success', 'Izin diterima.');
        }

        return redirect()->back()->with('error', 'Cicilan tidak ditemukan.');
    }
    
    public function tolak(Request $request, $id)
    {
        $pengajuan = Pengajuan::find($id);
        if ($pengajuan) {
            $pengajuan->status_approved = '2';
            $pengajuan->save();

            return redirect()->back()->with('success', 'Izin ditolak.');
        }

        return redirect()->back()->with('error', 'Cicilan tidak ditemukan.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
