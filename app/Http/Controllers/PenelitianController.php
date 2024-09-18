<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Penelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PenelitianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id_user = Auth::user()->id_user;
        $dosen = Dosen::where('id_user', $id_user)->first();

        return view('admin.penelitian', [
            'role' => Auth::user()->role,
            'active' => 'penelitian',
            'title' => 'Penelitian',
            'id_user' => $id_user,
            'dosen' => $dosen,
            'penelitian' => Penelitian::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function terima(Request $request, $id_penelitian)
    {
        DB::transaction(function () use ($id_penelitian) {
            $penelitian = Penelitian::find($id_penelitian);

            if ($penelitian) {
                $penelitian->verifikasi = 'diterima';
                $penelitian->save();

                DB::table('presensi')->insert([
                    'nip' => $penelitian->dosen->nip,
                    'tgl_presensi' => now()->toDateString(),
                    'jam_in' => now()->toTimeString(),
                    'jam_out' => now()->toTimeString(),
                    'foto_in' => $penelitian->bukti,
                    'foto_out' => $penelitian->bukti,
                    'lokasi_in' => $penelitian->lokasi,
                    'lokasi_out' => $penelitian->lokasi,
                    'keterangan_in' => 'Penelitian diterima',
                    'keterangan_out' => 'Penelitian diterima'
                ]);

                return redirect()->back()->with('success', 'Izin penelitian diterima dan presensi dicatat.');
            }

            return redirect()->back()->with('error', 'Penelitian tidak ditemukan.');
        });
    }

    public function tolak(Request $request, $id_penelitian)
    {
        $penelitian = Penelitian::find($id_penelitian);
        if ($penelitian) {
            $penelitian->verifikasi = 'ditolak';
            $penelitian->save();

            return redirect()->back()->with('success', 'Izin penelitian ditolak.');
        }

        return redirect()->back()->with('error', 'Penelitian tidak ditemukan.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create()
    {
        return view('presensi.penelitian');
    }

    /**
     * Display the specified resource.
     */
    public function post(Request $request)
    {
        // Fetch the authenticated user's associated dosen
        $dosen = DB::table('dosen')->where('nip', Auth::user()->nip)->first();

        if (!$dosen) {
            return redirect()->back()
                ->withErrors(['error' => 'Dosen tidak ditemukan.'])
                ->with('toastr', [
                    'message' => 'Dosen tidak ditemukan. Silakan periksa akun Anda.',
                    'type' => 'error'
                ]);
        }

        // Validation rules
        $rules = [
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'bukti' => 'required|file|mimes:jpg,jpeg,png|max:2048', // Ensure the file is an image
        ];

        // Custom error messages
        $messages = [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'bukti.required' => 'Bukti penelitian wajib diunggah.',
            'bukti.mimes' => 'Format file harus berupa .jpg, .jpeg, atau .png.',
            'bukti.max' => 'Ukuran file maksimal adalah 2MB.',
        ];

        // Validate form input
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('toastr', [
                    'message' => 'Ada kesalahan dalam formulir yang Anda isi. Silakan periksa kembali.',
                    'type' => 'error'
                ]);
        }

        // Get the validated data
        $validatedData = $validator->validated();

        // Save the penelitian data without the file first
        $penelitian = Penelitian::create([
            'id_dosen' => $dosen->id,
            'tanggal' => $validatedData['tanggal'],
            'lokasi' => $validatedData['lokasi'],
            'bukti' => null, // Placeholder for the file path
            'verifikasi' => 'menunggu', // Set a default status for 'verifikasi'
        ]);

        // Handle file upload if present
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $fileName = time() . '_' . $file->getClientOriginalName(); // Generate a unique name for the file

            // Check if a file with the same name already exists
            $existingFile = Penelitian::where('bukti', $fileName)->first();
            if ($existingFile) {
                return redirect()->back()
                    ->withErrors(['error' => 'File sudah ada di sistem.'])
                    ->withInput();
            }

            // Store the file and get the path
            $filePath = $file->storeAs('public/uploads/absensi', $fileName);
            $penelitian->bukti = str_replace('public/', '', $filePath); // Remove 'public/' for easier access later
            $penelitian->save(); // Update the record with the file path
        }

        return redirect()->back()->with('success', 'Penelitian berhasil diajukan.');
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
