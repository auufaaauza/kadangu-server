<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ArtistGroup;
use Illuminate\Http\Request;

class ArtistGroupController extends Controller
{
    /**
     * Display a listing of artist groups
     */
    public function index(Request $request)
    {
        $query = ArtistGroup::query();

        // Search by nama
        if ($request->has('search')) {
            $query->where('nama', 'like', "%{$request->search}%");
        }

        // Filter by kategori
        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $artistGroups = $query->paginate($request->get('per_page', 12));

        return response()->json($artistGroups);
    }

    /**
     * Display the specified artist group with their pertunjukans
     */
    public function show($id)
    {
        $artistGroup = ArtistGroup::with(['pertunjukans' => function($query) {
            $query->where('status', 'active')
                  ->orderBy('tanggal_pertunjukan', 'asc');
        }])->findOrFail($id);
        
        return response()->json($artistGroup);
    }
}
