<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Display a listing of published beritas
     */
    public function index(Request $request)
    {
        $query = Berita::with('penulis')
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc');

        // Search by judul or konten
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        // Filter by kategori
        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $beritas = $query->paginate($request->get('per_page', 10));

        return response()->json($beritas);
    }

    /**
     * Display the specified berita
     */
    public function show($id)
    {
        $berita = Berita::with('penulis')->findOrFail($id);
        
        return response()->json($berita);
    }
}
