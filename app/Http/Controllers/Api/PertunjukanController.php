<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pertunjukan;
use Illuminate\Http\Request;

class PertunjukanController extends Controller
{
    /**
     * Display a listing of pertunjukans with search and filter
     */
    public function index(Request $request)
    {
        $query = Pertunjukan::with('artistGroup')->where('status', 'active');

        // Search by judul or lokasi
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        // Filter by artist group
        if ($request->has('artist_group_id')) {
            $query->where('artist_group_id', $request->artist_group_id);
        }

        // Filter by price range
        if ($request->has('min_harga')) {
            $query->where('harga', '>=', $request->min_harga);
        }
        if ($request->has('max_harga')) {
            $query->where('harga', '<=', $request->max_harga);
        }

        // Filter by date
        if ($request->has('tanggal_dari')) {
            $query->where('tanggal_pertunjukan', '>=', $request->tanggal_dari);
        }
        if ($request->has('tanggal_sampai')) {
            $query->where('tanggal_pertunjukan', '<=', $request->tanggal_sampai);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'tanggal_pertunjukan');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $pertunjukans = $query->paginate($request->get('per_page', 12));

        return response()->json($pertunjukans);
    }

    /**
     * Display the specified pertunjukan
     */
    public function show($id)
    {
        $pertunjukan = Pertunjukan::with(['artistGroup', 'ticketCategories'])->findOrFail($id);
        
        return response()->json($pertunjukan);
    }
}
