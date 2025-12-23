<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    /**
     * Display a listing of all beritas
     */
    public function index(Request $request)
    {
        $beritas = Berita::with('penulis')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($beritas);
    }

    /**
     * Store a newly created berita
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->except('gambar');
        $data['penulis_id'] = $request->user()->id;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('beritas', 'public');
        }

        $berita = Berita::create($data);

        return response()->json([
            'message' => 'Berita berhasil dibuat',
            'berita' => $berita->load('penulis')
        ], 201);
    }

    /**
     * Display the specified berita
     */
    public function show($id)
    {
        $berita = Berita::with('penulis')->findOrFail($id);
        return response()->json($berita);
    }

    /**
     * Update the specified berita
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul' => 'sometimes|string|max:255',
            'konten' => 'sometimes|string',
            'kategori' => 'sometimes|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('beritas', 'public');
        }

        $berita->update($data);

        return response()->json([
            'message' => 'Berita berhasil diupdate',
            'berita' => $berita->load('penulis')
        ]);
    }

    /**
     * Remove the specified berita
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return response()->json([
            'message' => 'Berita berhasil dihapus'
        ]);
    }
}
