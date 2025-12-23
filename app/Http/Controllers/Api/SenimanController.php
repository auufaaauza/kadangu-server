<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seniman;
use Illuminate\Http\Request;

class SenimanController extends Controller
{
    /**
     * Display a listing of senimans
     */
    public function index(Request $request)
    {
        $query = Seniman::query();

        // Search by nama
        if ($request->has('search')) {
            $query->where('nama', 'like', "%{$request->search}%");
        }

        // Filter by kategori
        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $senimans = $query->paginate($request->get('per_page', 12));

        return response()->json($senimans);
    }

    /**
     * Display the specified seniman with their pertunjukans
     */
    public function show($id)
    {
        $seniman = Seniman::with(['pertunjukans' => function($query) {
            $query->where('status', 'active')
                  ->orderBy('tanggal_pertunjukan', 'asc');
        }])->findOrFail($id);
        
        return response()->json($seniman);
    }
}
