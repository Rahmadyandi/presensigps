<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Admin_DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id_user = $request->session()->get('id_user');
        $dosen = Dosen::where('id_user', $id_user)->first();

        return view('admin.dosen', [
            'title' => 'Dosen',
            'active' => 'dosen',
            'role' => $request->session()->get('role'),
            'dosen' => $dosen,
            'dosens' => Dosen::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function terima($id)
    {
        $dosen = Dosen::findOrFail($id);

        $dosen->persetujuan = 'diterima';
        $dosen->save();

        return redirect()->back()->with('success', 'Dosen telah diterima.');
    }

    public function tolak($id)
    {
        $dosen = Dosen::findOrFail($id);

        $dosen->persetujuan = 'ditolak';
        $dosen->save();

        return redirect()->back()->with('success', 'Dosen telah ditolak.');
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
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $dosen = Dosen::findOrFail($id);

            if ($dosen->id) {
                $user = User::findOrFail($dosen->id_user);
                $user->delete();
            }

            $dosen->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Dosen berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error dosen customer: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus customer dan pengguna terkait');
        }
    }
}
