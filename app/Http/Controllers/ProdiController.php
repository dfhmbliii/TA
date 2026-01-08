<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodi;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodis = Prodi::orderBy('id','desc')->get();
        return view('prodi.index', compact('prodis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('prodi.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_prodi' => ['required','string','max:255'],
            'nama_fakultas' => ['required','string','max:255'],
            'kode_prodi' => ['required','string','max:50','unique:prodis,kode_prodi'],
            'deskripsi' => ['nullable','string'],
            'visi_misi' => ['nullable','string'],
            'prospek_kerja' => ['nullable','string'],
            'visi_misi_url' => ['nullable','url','max:500'],
            'prospek_url' => ['nullable','url','max:500'],
            'kurikulum_url' => ['nullable','url','max:500'],
            'kurikulum_embed' => ['nullable','boolean'],
        ]);

        Prodi::create($data);
        return redirect()->route('prodi.index')->with('success','Prodi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prodi = Prodi::findOrFail($id);
        return view('prodi.show', compact('prodi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prodi = Prodi::findOrFail($id);
        if (request()->wantsJson()) {
            return response()->json($prodi);
        }
        return redirect()->route('prodi.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $prodi = Prodi::findOrFail($id);
        
        try {
            $data = $request->validate([
                'nama_prodi' => ['required','string','max:255'],
                'nama_fakultas' => ['required','string','max:255'],
                'kode_prodi' => ['required','string','max:50','unique:prodis,kode_prodi,'.$prodi->id],
                'deskripsi' => ['nullable','string'],
                'visi_misi' => ['nullable','string'],
                'prospek_kerja' => ['nullable','string'],
                'visi_misi_url' => ['nullable','url','max:500'],
                'prospek_url' => ['nullable','url','max:500'],
                'kurikulum_url' => ['nullable','url','max:500'],
                'kurikulum_embed' => ['nullable','boolean'],
            ]);

            // Handle checkbox value
            $data['kurikulum_embed'] = $request->has('kurikulum_embed') ? true : false;

            $prodi->update($data);
            
            return redirect()->route('prodi.index')
                ->with('success','Program studi berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('prodi.index')
                ->withErrors($e->validator)
                ->withInput()
                ->with('editProdiId', $id); // This will trigger the modal to reopen
        } catch (\Exception $e) {
            return redirect()->route('prodi.index')
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui program studi.'])
                ->withInput()
                ->with('editProdiId', $id); // This will trigger the modal to reopen
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();
        return redirect()->route('prodi.index')->with('success','Prodi berhasil dihapus.');
    }

    /**
     * Show curriculum edit page
     */
    public function editKurikulum(string $id)
    {
        $prodi = Prodi::findOrFail($id);
        return view('prodi.kurikulum', compact('prodi'));
    }

    /**
     * Update curriculum data
     */
    public function updateKurikulum(Request $request, string $id)
    {
        $prodi = Prodi::findOrFail($id);
        
        $request->validate([
            'total_sks' => 'required|integer|min:0',
            'jumlah_semester' => 'required|integer|min:1|max:14',
            'kurikulum_data' => 'required|json',
        ]);

        // Parse and validate JSON
        $kurikulumData = json_decode($request->kurikulum_data, true);
        
        if (!is_array($kurikulumData)) {
            return back()->with('error', 'Format data kurikulum tidak valid.');
        }

        // Update prodi
        $prodi->kurikulum_data = $kurikulumData;
        $prodi->total_sks = $request->total_sks;
        $prodi->jumlah_semester = $request->jumlah_semester;
        $prodi->save();

        return redirect()->route('prodi.kurikulum', $prodi->id)
            ->with('success', 'Kurikulum berhasil diperbarui!');
    }
}
