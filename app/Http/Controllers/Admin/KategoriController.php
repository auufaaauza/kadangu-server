<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pertunjukan;
use App\Models\Seniman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    private $kategoriMap = [
        'musik' => 'Musik',
        'tari' => 'Tari',
        'teater' => 'Teater',
        'seni-rupa' => 'Seni Rupa',
        'sastra' => 'Sastra',
        'film' => 'Film',
        'budaya' => 'Budaya',
        'workshop' => 'Workshop',
    ];

    public function index($kategori)
    {
        $kategoriName = $this->kategoriMap[$kategori] ?? ucfirst($kategori);
        
        $pertunjukans = Pertunjukan::with('seniman')
            ->whereHas('seniman', function($query) use ($kategoriName) {
                $query->where('nama', 'LIKE', '%' . $kategoriName . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.kategori.index', compact('pertunjukans', 'kategori', 'kategoriName'));
    }

    public function create($kategori)
    {
        $kategoriName = $this->kategoriMap[$kategori] ?? ucfirst($kategori);
        return view('admin.kategori.create', compact('kategori', 'kategoriName'));
    }

    public function store(Request $request, $kategori)
    {
        $kategoriName = $this->kategoriMap[$kategori] ?? ucfirst($kategori);
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_pertunjukan' => 'required|date',
            'lokasi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'biaya_layanan' => 'required|numeric|min:0|max:100',
            'ppn' => 'required|numeric|min:0|max:100',
            'kuota' => 'required|integer|min:1',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Auto-create seniman with kategori name
        $seniman = Seniman::firstOrCreate(
            ['nama' => $kategoriName],
            ['bio' => 'Kategori ' . $kategoriName, 'kategori' => $kategoriName]
        );

        $validated['seniman_id'] = $seniman->id;
        $validated['kuota_tersisa'] = $validated['kuota'];

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('pertunjukans', 'public');
        }

        Pertunjukan::create($validated);

        return redirect()->route('admin.kategori.index', $kategori)
            ->with('success', ucfirst($kategoriName) . ' berhasil ditambahkan!');
    }

    public function edit($kategori, $id)
    {
        $kategoriName = $this->kategoriMap[$kategori] ?? ucfirst($kategori);
        $pertunjukan = Pertunjukan::findOrFail($id);
        
        return view('admin.kategori.edit', compact('pertunjukan', 'kategori', 'kategoriName'));
    }

    public function update(Request $request, $kategori, $id)
    {
        $kategoriName = $this->kategoriMap[$kategori] ?? ucfirst($kategori);
        $pertunjukan = Pertunjukan::findOrFail($id);
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_pertunjukan' => 'required|date',
            'lokasi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'biaya_layanan' => 'required|numeric|min:0|max:100',
            'ppn' => 'required|numeric|min:0|max:100',
            'kuota' => 'required|integer|min:1',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->kuota != $pertunjukan->kuota) {
            $diff = $request->kuota - $pertunjukan->kuota;
            $validated['kuota_tersisa'] = $pertunjukan->kuota_tersisa + $diff;
        }

        if ($request->hasFile('gambar')) {
            if ($pertunjukan->gambar) {
                Storage::disk('public')->delete($pertunjukan->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('pertunjukans', 'public');
        }

        $pertunjukan->update($validated);

        return redirect()->route('admin.kategori.index', $kategori)
            ->with('success', ucfirst($kategoriName) . ' berhasil diupdate!');
    }

    public function destroy($kategori, $id)
    {
        $pertunjukan = Pertunjukan::findOrFail($id);
        
        if ($pertunjukan->gambar) {
            Storage::disk('public')->delete($pertunjukan->gambar);
        }

        $pertunjukan->delete();

        return redirect()->route('admin.kategori.index', $kategori)
            ->with('success', 'Data berhasil dihapus!');
    }
}
