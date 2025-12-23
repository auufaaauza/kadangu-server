<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Talent;
use App\Models\Seniman;
use App\Models\TalentPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TalentController extends Controller
{
    public function index(Request $request)
    {
        $query = Talent::with(['seniman', 'packages', 'bookings']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->whereHas('seniman', function($q) use ($request) {
                $q->where('id', $request->kategori);
            });
        }

        $talents = $query->orderBy('created_at', 'desc')->paginate(15);
        // Only show art/seni categories for talents
        $senimans = Seniman::whereIn('nama', ['Musik', 'Tari', 'Teater', 'Seni Rupa', 'Sastra', 'Film'])->get();

        return view('admin.talent.index', compact('talents', 'senimans'));
    }

    public function create()
    {
        // Only show art/seni categories for talents
        $senimans = Seniman::whereIn('nama', ['Musik', 'Tari', 'Teater', 'Seni Rupa', 'Sastra', 'Film'])->get();
        return view('admin.talent.create', compact('senimans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'seniman_id' => 'required|exists:senimans,id',
            'bio' => 'required|string',
            'genre' => 'required|string|max:100',
            'base_price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'portfolio.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'service_description' => 'nullable|string',
            'availability_status' => 'required|in:available,booked,unavailable',
            'status' => 'required|in:active,inactive',
            'packages' => 'required|array|min:1',
            'packages.*.name' => 'required|string|max:255',
            'packages.*.price' => 'required',
            'packages.*.duration_hours' => 'required|integer|min:1',
            'packages.*.description' => 'nullable|string',
            'packages.*.includes' => 'nullable|string',
            'packages.*.status' => 'required|in:active,inactive',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('talents/photos', 'public');
        }

        // Handle portfolio uploads
        $portfolioFiles = [];
        if ($request->hasFile('portfolio')) {
            foreach ($request->file('portfolio') as $file) {
                $portfolioFiles[] = $file->store('talents/portfolio', 'public');
            }
        }
        $validated['portfolio'] = $portfolioFiles;

        // Remove rupiah formatting from base_price
        $validated['base_price'] = (int) str_replace(['.', ',', 'Rp', ' '], '', $validated['base_price']);

        // Remove packages from validated data (we'll handle separately)
        $packages = $validated['packages'];
        unset($validated['packages']);

        // Create talent
        $talent = Talent::create($validated);

        // Create packages
        foreach ($packages as $packageData) {
            // Remove rupiah formatting from price
            $packageData['price'] = (int) str_replace(['.', ',', 'Rp', ' '], '', $packageData['price']);
            
            // Convert includes from textarea (string with newlines) to array
            if (isset($packageData['includes']) && is_string($packageData['includes'])) {
                $includes = array_filter(array_map('trim', explode("\n", $packageData['includes'])));
                $packageData['includes'] = array_values($includes);
            } else {
                $packageData['includes'] = [];
            }
            
            $talent->packages()->create($packageData);
        }

        return redirect()->route('admin.talent.index')
            ->with('success', 'Talent berhasil ditambahkan!');
    }

    public function show($id)
    {
        $talent = Talent::with(['seniman', 'packages', 'bookings.user', 'bookings.package'])
            ->findOrFail($id);

        return view('admin.talent.show', compact('talent'));
    }

    public function edit($id)
    {
        $talent = Talent::with('packages')->findOrFail($id);
        // Only show art/seni categories for talents
        $senimans = Seniman::whereIn('nama', ['Musik', 'Tari', 'Teater', 'Seni Rupa', 'Sastra', 'Film'])->get();
        
        return view('admin.talent.edit', compact('talent', 'senimans'));
    }

    public function update(Request $request, $id)
    {
        $talent = Talent::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'seniman_id' => 'required|exists:senimans,id',
            'bio' => 'required|string',
            'genre' => 'required|string|max:100',
            'base_price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'portfolio.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'service_description' => 'nullable|string',
            'availability_status' => 'required|in:available,booked,unavailable',
            'status' => 'required|in:active,inactive',
            'packages' => 'required|array|min:1',
            'packages.*.id' => 'nullable|exists:talent_packages,id',
            'packages.*.name' => 'required|string|max:255',
            'packages.*.price' => 'required',
            'packages.*.duration_hours' => 'required|integer|min:1',
            'packages.*.description' => 'nullable|string',
            'packages.*.includes' => 'nullable|string',
            'packages.*.status' => 'required|in:active,inactive',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($talent->photo) {
                Storage::disk('public')->delete($talent->photo);
            }
            $validated['photo'] = $request->file('photo')->store('talents/photos', 'public');
        }

        // Handle portfolio uploads
        if ($request->hasFile('portfolio')) {
            // Delete old portfolio files
            if ($talent->portfolio) {
                foreach ($talent->portfolio as $file) {
                    Storage::disk('public')->delete($file);
                }
            }
            
            $portfolioFiles = [];
            foreach ($request->file('portfolio') as $file) {
                $portfolioFiles[] = $file->store('talents/portfolio', 'public');
            }
            $validated['portfolio'] = $portfolioFiles;
        }

        // Remove rupiah formatting from base_price
        $validated['base_price'] = (int) str_replace(['.', ',', 'Rp', ' '], '', $validated['base_price']);

        // Update talent
        $talent->update($validated);

        // Sync packages
        $existingPackageIds = [];
        foreach ($request->packages as $packageData) {
            // Remove rupiah formatting from price
            $packageData['price'] = (int) str_replace(['.', ',', 'Rp', ' '], '', $packageData['price']);
            
            // Convert includes from textarea (string with newlines) to array
            if (isset($packageData['includes']) && is_string($packageData['includes'])) {
                $includes = array_filter(array_map('trim', explode("\n", $packageData['includes'])));
                $packageData['includes'] = array_values($includes);
            } else {
                $packageData['includes'] = [];
            }
            
            if (isset($packageData['id']) && $packageData['id']) {
                // Update existing package
                $package = TalentPackage::find($packageData['id']);
                if ($package && $package->talent_id == $talent->id) {
                    $package->update($packageData);
                    $existingPackageIds[] = $package->id;
                }
            } else {
                // Create new package
                $newPackage = $talent->packages()->create($packageData);
                $existingPackageIds[] = $newPackage->id;
            }
        }

        // Delete packages that are not in the request
        $talent->packages()->whereNotIn('id', $existingPackageIds)->delete();

        return redirect()->route('admin.talent.index')
            ->with('success', 'Talent berhasil diupdate!');
    }

    public function destroy($id)
    {
        $talent = Talent::findOrFail($id);

        // Delete photo
        if ($talent->photo) {
            Storage::disk('public')->delete($talent->photo);
        }

        // Delete portfolio files
        if ($talent->portfolio) {
            foreach ($talent->portfolio as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $talent->delete();

        return redirect()->route('admin.talent.index')
            ->with('success', 'Talent berhasil dihapus!');
    }
}
