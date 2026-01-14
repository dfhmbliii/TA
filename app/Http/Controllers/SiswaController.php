<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswas = \App\Models\Siswa::paginate(10);
        return view('siswa.index', compact('siswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:50', 'unique:siswas,nisn'],
            'email' => ['required', 'email', 'unique:siswas,email', 'unique:users,email'],
            'jurusan_sma' => ['required', 'string', 'max:255'],
            'asal_sekolah' => ['required', 'string', 'max:255'],
            'tahun_lulus' => ['required', 'digits:4'],
            'ipk' => ['nullable', 'numeric', 'between:0,4'],
            'prestasi_score' => ['nullable', 'numeric'],
            'kepemimpinan' => ['nullable', 'numeric', 'between:0,100'],
            'sosial' => ['nullable', 'numeric', 'between:0,100'],
            'komunikasi' => ['nullable', 'numeric', 'between:0,100'],
            'kreativitas' => ['nullable', 'numeric', 'between:0,100'],
        ]);

        // Create siswa record
        $siswa = \App\Models\Siswa::create($data);
        
        // Create user account with NISN as default password
        \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['nisn']),
            'role' => 'siswa',
        ]);
        
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan. Password default: ' . $data['nisn']);
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
        $siswa = \App\Models\Siswa::findOrFail($id);
        if (request()->wantsJson()) {
            return response()->json($siswa);
        }
        return redirect()->route('siswa.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = \App\Models\Siswa::findOrFail($id);
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:50', 'unique:siswas,nisn,' . $siswa->id],
            'email' => ['required', 'email', 'unique:siswas,email,' . $siswa->id],
            'jurusan_sma' => ['required', 'string', 'max:255'],
            'asal_sekolah' => ['required', 'string', 'max:255'],
            'tahun_lulus' => ['required', 'digits:4'],
            'ipk' => ['nullable', 'numeric', 'between:0,4'],
            'prestasi_score' => ['nullable', 'numeric'],
            'kepemimpinan' => ['nullable', 'numeric', 'between:0,100'],
            'sosial' => ['nullable', 'numeric', 'between:0,100'],
            'komunikasi' => ['nullable', 'numeric', 'between:0,100'],
            'kreativitas' => ['nullable', 'numeric', 'between:0,100'],
        ]);

        $siswa->update($data);
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = \App\Models\Siswa::findOrFail($id);
        
        // Delete user account if exists
        $user = \App\Models\User::where('email', $siswa->email)->first();
        if ($user) {
            $user->delete();
        }
        
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
    
    /**
     * Reset password for siswa
     */
    public function resetPassword(string $id)
    {
        $siswa = \App\Models\Siswa::findOrFail($id);
        $user = \App\Models\User::where('email', $siswa->email)->first();
        
        if (!$user) {
            return redirect()->route('siswa.index')->with('error', 'User tidak ditemukan.');
        }
        
        // Reset password to default (nisn)
        $user->password = Hash::make($siswa->nisn);
        $user->save();
        
        return redirect()->route('siswa.index')->with('success', 'Password berhasil direset menjadi NISN: ' . $siswa->nisn);
    }
}
