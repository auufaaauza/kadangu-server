<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Talent;
use Illuminate\Http\Request;

class TalentController extends Controller
{
    /**
     * Display a listing of talents
     */
    public function index(Request $request)
    {
        $query = Talent::with(['artistGroup', 'packages']);

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by genre
        if ($request->has('genre')) {
            $query->where('genre', $request->genre);
        }

        // Filter by status
        $query->where('status', 'active');

        $talents = $query->paginate($request->get('per_page', 12));

        return response()->json($talents);
    }

    /**
     * Display the specified talent
     */
    public function show($id)
    {
        $talent = Talent::with(['artistGroup', 'packages'])->findOrFail($id);
        
        return response()->json($talent);
    }
}
