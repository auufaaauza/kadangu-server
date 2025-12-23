<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pertunjukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PertunjukanController extends Controller
{
    /**
     * Display a listing of all pertunjukans
     */
    public function index(Request $request)
    {
        $query = Pertunjukan::with('seniman');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $pertunjukans = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($pertunjukans);
    }

    /**
     * Store a newly created pertunjukan
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_pertunjukan' => 'required|date',
            'lokasi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:1',
            'seniman_id' => 'required|exists:senimans,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable|in:active,inactive',
        ]);

        $data = $request->except('gambar');
        $data['kuota_tersisa'] = $request->kuota;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('pertunjukans', 'public');
        }

        $pertunjukan = Pertunjukan::create($data);

        return response()->json([
            'message' => 'Pertunjukan berhasil dibuat',
            'pertunjukan' => $pertunjukan->load('seniman')
        ], 201);
    }

    /**
     * Display the specified pertunjukan
     */
    public function show($id)
    {
        $pertunjukan = Pertunjukan::with('seniman', 'bookings')->findOrFail($id);
        return response()->json($pertunjukan);
    }

    /**
     * Update the specified pertunjukan
     */
    public function update(Request $request, $id)
    {
        $pertunjukan = Pertunjukan::findOrFail($id);

        $request->validate([
            'judul' => 'sometimes|string|max:255',
            'deskripsi' => 'sometimes|string',
            'tanggal_pertunjukan' => 'sometimes|date',
            'lokasi' => 'sometimes|string',
            'harga' => 'sometimes|numeric|min:0',
            'kuota' => 'sometimes|integer|min:1',
            'seniman_id' => 'sometimes|exists:senimans,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $data = $request->except('gambar');

        // Update kuota_tersisa if kuota changed
        if ($request->has('kuota')) {
            $diff = $request->kuota - $pertunjukan->kuota;
            $data['kuota_tersisa'] = $pertunjukan->kuota_tersisa + $diff;
        }

        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($pertunjukan->gambar) {
                Storage::disk('public')->delete($pertunjukan->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('pertunjukans', 'public');
        }

        $pertunjukan->update($data);

        return response()->json([
            'message' => 'Pertunjukan berhasil diupdate',
            'pertunjukan' => $pertunjukan->load('seniman')
        ]);
    }

    /**
     * Remove the specified pertunjukan
     */
    public function destroy($id)
    {
        $pertunjukan = Pertunjukan::findOrFail($id);

        // Delete image if exists
        if ($pertunjukan->gambar) {
            Storage::disk('public')->delete($pertunjukan->gambar);
        }

        $pertunjukan->delete();

        return response()->json([
            'message' => 'Pertunjukan berhasil dihapus'
        ]);
    }
}
