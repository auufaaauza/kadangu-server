<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArtistGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtistGroupController extends Controller
{
    /**
     * Display a listing of all artist groups
     */
    public function index(Request $request)
    {
        $artistGroups = ArtistGroup::withCount('pertunjukans')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($artistGroups);
    }

    /**
     * Store a newly created artist group
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'bio' => 'required|string',
            'kategori' => 'required|string',
            'kontak' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('artist_groups', 'public');
        }

        $artistGroup = ArtistGroup::create($data);

        return response()->json([
            'message' => 'Artist group berhasil dibuat',
            'artistGroup' => $artistGroup
        ], 201);
    }

    /**
     * Display the specified artist group
     */
    public function show($id)
    {
        $artistGroup = ArtistGroup::with('pertunjukans')->findOrFail($id);
        return response()->json($artistGroup);
    }

    /**
     * Update the specified artist group
     */
    public function update(Request $request, $id)
    {
        $artistGroup = ArtistGroup::findOrFail($id);

        $request->validate([
            'nama' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string',
            'kategori' => 'sometimes|string',
            'kontak' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            if ($artistGroup->foto) {
                Storage::disk('public')->delete($artistGroup->foto);
            }
            $data['foto'] = $request->file('foto')->store('artist_groups', 'public');
        }

        $artistGroup->update($data);

        return response()->json([
            'message' => 'Artist group berhasil diupdate',
            'artistGroup' => $artistGroup
        ]);
    }

    /**
     * Remove the specified artist group
     */
    public function destroy($id)
    {
        $artistGroup = ArtistGroup::findOrFail($id);

        if ($artistGroup->foto) {
            Storage::disk('public')->delete($artistGroup->foto);
        }

        $artistGroup->delete();

        return response()->json([
            'message' => 'Artist group berhasil dihapus'
        ]);
    }
}
