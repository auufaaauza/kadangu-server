<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pertunjukan;
use App\Models\ArtistGroup;
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
        
        $pertunjukans = Pertunjukan::with('artistGroup')
            ->whereHas('artistGroup', function($query) use ($kategoriName) {
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
            'biaya_layanan' => 'required|numeric|min:0|max:100',
            'ppn' => 'required|numeric|min:0|max:100',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive,passed,coming_soon',
            'ticket_categories' => 'required|array|min:1',
            'ticket_categories.*.nama' => 'required|string|max:255',
            'ticket_categories.*.harga' => 'required|numeric|min:0',
            'ticket_categories.*.kuota' => 'required|integer|min:1',
            'ticket_categories.*.deskripsi' => 'nullable|string',
        ]);

        // Auto-create artist group with kategori name
        $artistGroup = ArtistGroup::firstOrCreate(
            ['nama' => $kategoriName],
            ['bio' => 'Kategori ' . $kategoriName, 'kategori' => $kategoriName]
        );

        $validated['artist_group_id'] = $artistGroup->id;
        
        // Calculate total kuota from all categories
        $totalKuota = collect($validated['ticket_categories'])->sum('kuota');
        $validated['kuota'] = $totalKuota;
        $validated['kuota_tersisa'] = $totalKuota;
        // Set minimum price as base price (optional, since harga is nullable/0 default)
        $validated['harga'] = collect($validated['ticket_categories'])->min('harga');

        unset($validated['ticket_categories']);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('pertunjukans', 'public');
        }

        $pertunjukan = Pertunjukan::create($validated);

        // Create ticket categories
        foreach ($request->ticket_categories as $category) {
            $pertunjukan->ticketCategories()->create([
                'nama' => $category['nama'],
                'harga' => $category['harga'],
                'kuota' => $category['kuota'],
                'kuota_tersisa' => $category['kuota'],
                'deskripsi' => $category['deskripsi'] ?? null,
            ]);
        }

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
            'biaya_layanan' => 'required|numeric|min:0|max:100',
            'ppn' => 'required|numeric|min:0|max:100',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive,passed,coming_soon',
            'ticket_categories' => 'required|array|min:1',
            'ticket_categories.*.id' => 'nullable|exists:ticket_categories,id',
            'ticket_categories.*.nama' => 'required|string|max:255',
            'ticket_categories.*.harga' => 'required|numeric|min:0',
            'ticket_categories.*.kuota' => 'required|integer|min:1',
            'ticket_categories.*.deskripsi' => 'nullable|string',
        ]);

        // Auto-create artist group with kategori name
        $artistGroup = ArtistGroup::firstOrCreate(
            ['nama' => $kategoriName],
            ['bio' => 'Kategori ' . $kategoriName, 'kategori' => $kategoriName]
        );

        $validated['artist_group_id'] = $artistGroup->id;

        // Calculate total kuota from all categories
        $totalKuota = collect($validated['ticket_categories'])->sum('kuota');
        $validated['kuota'] = $totalKuota;
        $validated['harga'] = collect($validated['ticket_categories'])->min('harga');

        // Update kuota_tersisa if kuota changed
        if ($totalKuota != $pertunjukan->kuota) {
            $diff = $totalKuota - $pertunjukan->kuota;
            $validated['kuota_tersisa'] = $pertunjukan->kuota_tersisa + $diff;
        }

        unset($validated['ticket_categories']);

        if ($request->hasFile('gambar')) {
            if ($pertunjukan->gambar) {
                Storage::disk('public')->delete($pertunjukan->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('pertunjukans', 'public');
        }

        $pertunjukan->update($validated);

        // Sync ticket categories
        $existingIds = [];
        foreach ($request->ticket_categories as $category) {
            if (isset($category['id']) && $category['id']) {
                // Update existing category
                $ticketCategory = \App\Models\TicketCategory::find($category['id']);
                if ($ticketCategory) {
                    $oldKuota = $ticketCategory->kuota;
                    $newKuota = $category['kuota'];
                    $diff = $newKuota - $oldKuota;
                    
                    $ticketCategory->update([
                        'nama' => $category['nama'],
                        'harga' => $category['harga'],
                        'kuota' => $newKuota,
                        'kuota_tersisa' => $ticketCategory->kuota_tersisa + $diff,
                        'deskripsi' => $category['deskripsi'] ?? null,
                    ]);
                    $existingIds[] = $category['id'];
                }
            } else {
                // Create new category
                $newCategory = $pertunjukan->ticketCategories()->create([
                    'nama' => $category['nama'],
                    'harga' => $category['harga'],
                    'kuota' => $category['kuota'],
                    'kuota_tersisa' => $category['kuota'],
                    'deskripsi' => $category['deskripsi'] ?? null,
                ]);
                $existingIds[] = $newCategory->id;
            }
        }
        
        // Delete categories that are no longer in the list
        $pertunjukan->ticketCategories()->whereNotIn('id', $existingIds)->delete();

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
