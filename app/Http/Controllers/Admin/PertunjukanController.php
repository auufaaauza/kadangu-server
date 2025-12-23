<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pertunjukan;
use App\Models\Seniman;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PertunjukanController extends Controller
{
    public function index()
    {
        $pertunjukans = Pertunjukan::with(['seniman', 'ticketCategories'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.pertunjukan.index', compact('pertunjukans'));
    }

    public function create()
    {
        return view('admin.pertunjukan.create');
    }

    public function show(Pertunjukan $pertunjukan)
    {
        $pertunjukan->load(['seniman', 'ticketCategories']);
        
        $bookings = $pertunjukan->bookings()
            ->with(['user', 'ticketCategory', 'transaction'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.pertunjukan.show', compact('pertunjukan', 'bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_pertunjukan' => 'required|date',
            'lokasi' => 'required|string',
            'biaya_layanan' => 'required|numeric|min:0|max:100',
            'ppn' => 'required|numeric|min:0|max:100',
            'seniman_nama' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive,passed',
            'ticket_categories' => 'required|array|min:1',
            'ticket_categories.*.nama' => 'required|string|max:255',
            'ticket_categories.*.harga' => 'required|numeric|min:0',
            'ticket_categories.*.kuota' => 'required|integer|min:1',
            'ticket_categories.*.deskripsi' => 'nullable|string',
        ]);

        // Find or create seniman
        $seniman = Seniman::firstOrCreate(
            ['nama' => $validated['seniman_nama']],
            ['bio' => '', 'kategori' => 'Umum']
        );

        $validated['seniman_id'] = $seniman->id;
        
        // Calculate total kuota from all categories
        $totalKuota = collect($validated['ticket_categories'])->sum('kuota');
        $validated['kuota'] = $totalKuota;
        $validated['kuota_tersisa'] = $totalKuota;
        
        unset($validated['seniman_nama']);
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

        return redirect()->route('admin.pertunjukan.index')
            ->with('success', 'Pertunjukan berhasil ditambahkan!');
    }

    public function edit(Pertunjukan $pertunjukan)
    {
        return view('admin.pertunjukan.edit', compact('pertunjukan'));
    }

    public function update(Request $request, Pertunjukan $pertunjukan)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_pertunjukan' => 'required|date',
            'lokasi' => 'required|string',
            'biaya_layanan' => 'required|numeric|min:0|max:100',
            'ppn' => 'required|numeric|min:0|max:100',
            'seniman_nama' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive,passed',
            'ticket_categories' => 'required|array|min:1',
            'ticket_categories.*.id' => 'nullable|exists:ticket_categories,id',
            'ticket_categories.*.nama' => 'required|string|max:255',
            'ticket_categories.*.harga' => 'required|numeric|min:0',
            'ticket_categories.*.kuota' => 'required|integer|min:1',
            'ticket_categories.*.deskripsi' => 'nullable|string',
        ]);

        // Find or create seniman
        $seniman = Seniman::firstOrCreate(
            ['nama' => $validated['seniman_nama']],
            ['bio' => '', 'kategori' => 'Umum']
        );

        $validated['seniman_id'] = $seniman->id;
        
        // Calculate total kuota from all categories
        $totalKuota = collect($validated['ticket_categories'])->sum('kuota');
        $validated['kuota'] = $totalKuota;
        
        // Update kuota_tersisa if kuota changed
        if ($totalKuota != $pertunjukan->kuota) {
            $diff = $totalKuota - $pertunjukan->kuota;
            $validated['kuota_tersisa'] = $pertunjukan->kuota_tersisa + $diff;
        }
        
        unset($validated['seniman_nama']);
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
                $ticketCategory = TicketCategory::find($category['id']);
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

        return redirect()->route('admin.pertunjukan.index')
            ->with('success', 'Pertunjukan berhasil diupdate!');
    }

    public function destroy(Pertunjukan $pertunjukan)
    {
        if ($pertunjukan->gambar) {
            Storage::disk('public')->delete($pertunjukan->gambar);
        }

        $pertunjukan->delete();

        return redirect()->route('admin.pertunjukan.index')
            ->with('success', 'Pertunjukan berhasil dihapus!');
    }
}
