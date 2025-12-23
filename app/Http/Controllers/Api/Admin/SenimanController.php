<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seniman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SenimanController extends Controller
{
    /**
     * Display a listing of all senimans
     */
    public function index(Request $request)
    {
        $senimans = Seniman::withCount('pertunjukans')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($senimans);
    }

    /**
     * Store a newly created seniman
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
            $data['foto'] = $request->file('foto')->store('senimans', 'public');
        }

        $seniman = Seniman::create($data);

        return response()->json([
            'message' => 'Seniman berhasil dibuat',
            'seniman' => $seniman
        ], 201);
    }

    /**
     * Display the specified seniman
     */
    public function show($id)
    {
        $seniman = Seniman::with('pertunjukans')->findOrFail($id);
        return response()->json($seniman);
    }

    /**
     * Update the specified seniman
     */
    public function update(Request $request, $id)
    {
        $seniman = Seniman::findOrFail($id);

        $request->validate([
            'nama' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string',
            'kategori' => 'sometimes|string',
            'kontak' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            if ($seniman->foto) {
                Storage::disk('public')->delete($seniman->foto);
            }
            $data['foto'] = $request->file('foto')->store('senimans', 'public');
        }

        $seniman->update($data);

        return response()->json([
            'message' => 'Seniman berhasil diupdate',
            'seniman' => $seniman
        ]);
    }

    /**
     * Remove the specified seniman
     */
    public function destroy($id)
    {
        $seniman = Seniman::findOrFail($id);

        if ($seniman->foto) {
            Storage::disk('public')->delete($seniman->foto);
        }

        $seniman->delete();

        return response()->json([
            'message' => 'Seniman berhasil dihapus'
        ]);
    }
}
