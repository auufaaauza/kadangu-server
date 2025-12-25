<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PaymentSettingController extends Controller
{
    /**
     * Display a listing of payment settings
     */
    public function index()
    {
        $paymentSettings = PaymentSetting::orderBy('display_order')->get();
        return view('admin.payment-settings.index', compact('paymentSettings'));
    }

    /**
     * Show the form for creating a new payment setting
     */
    public function create()
    {
        return view('admin.payment-settings.create');
    }

    /**
     * Store a newly created payment setting
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:qris,bank_account'],
            'name' => ['required', 'string', 'max:255'],
            'qris_image' => [
                Rule::requiredIf($request->type === 'qris'),
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048' // 2MB
            ],
            'bank_name' => [
                Rule::requiredIf($request->type === 'bank_account'),
                'nullable',
                'string',
                'max:100'
            ],
            'account_number' => [
                Rule::requiredIf($request->type === 'bank_account'),
                'nullable',
                'string',
                'max:50',
                'regex:/^[0-9]+$/' // Only numbers
            ],
            'account_holder' => [
                Rule::requiredIf($request->type === 'bank_account'),
                'nullable',
                'string',
                'max:255'
            ],
            'instructions' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'type.required' => 'Tipe pembayaran wajib dipilih',
            'type.in' => 'Tipe pembayaran tidak valid',
            'name.required' => 'Nama metode pembayaran wajib diisi',
            'qris_image.required_if' => 'Gambar QRIS wajib diupload untuk tipe QRIS',
            'qris_image.image' => 'File harus berupa gambar',
            'qris_image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'qris_image.max' => 'Ukuran gambar maksimal 2MB',
            'bank_name.required_if' => 'Nama bank wajib diisi untuk tipe Bank Account',
            'account_number.required_if' => 'Nomor rekening wajib diisi untuk tipe Bank Account',
            'account_number.regex' => 'Nomor rekening hanya boleh berisi angka',
            'account_holder.required_if' => 'Nama pemilik rekening wajib diisi untuk tipe Bank Account',
        ]);

        // Handle QRIS image upload
        if ($request->hasFile('qris_image')) {
            $path = $request->file('qris_image')->store('qris', 'public');
            $validated['qris_image'] = $path;
        }

        // Set default display order if not provided
        if (!isset($validated['display_order'])) {
            $maxOrder = PaymentSetting::max('display_order') ?? 0;
            $validated['display_order'] = $maxOrder + 1;
        }

        PaymentSetting::create($validated);

        return redirect()
            ->route('admin.payment-settings.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified payment setting
     */
    public function edit(PaymentSetting $paymentSetting)
    {
        return view('admin.payment-settings.edit', compact('paymentSetting'));
    }

    /**
     * Update the specified payment setting
     */
    public function update(Request $request, PaymentSetting $paymentSetting)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:qris,bank_account'],
            'name' => ['required', 'string', 'max:255'],
            'qris_image' => [
                Rule::requiredIf($request->type === 'qris' && !$paymentSetting->qris_image),
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],
            'bank_name' => [
                Rule::requiredIf($request->type === 'bank_account'),
                'nullable',
                'string',
                'max:100'
            ],
            'account_number' => [
                Rule::requiredIf($request->type === 'bank_account'),
                'nullable',
                'string',
                'max:50',
                'regex:/^[0-9]+$/'
            ],
            'account_holder' => [
                Rule::requiredIf($request->type === 'bank_account'),
                'nullable',
                'string',
                'max:255'
            ],
            'instructions' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'type.required' => 'Tipe pembayaran wajib dipilih',
            'type.in' => 'Tipe pembayaran tidak valid',
            'name.required' => 'Nama metode pembayaran wajib diisi',
            'qris_image.required_if' => 'Gambar QRIS wajib diupload untuk tipe QRIS',
            'qris_image.image' => 'File harus berupa gambar',
            'qris_image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'qris_image.max' => 'Ukuran gambar maksimal 2MB',
            'bank_name.required_if' => 'Nama bank wajib diisi untuk tipe Bank Account',
            'account_number.required_if' => 'Nomor rekening wajib diisi untuk tipe Bank Account',
            'account_number.regex' => 'Nomor rekening hanya boleh berisi angka',
            'account_holder.required_if' => 'Nama pemilik rekening wajib diisi untuk tipe Bank Account',
        ]);

        // Handle QRIS image upload
        if ($request->hasFile('qris_image')) {
            // Delete old image if exists
            if ($paymentSetting->qris_image) {
                Storage::disk('public')->delete($paymentSetting->qris_image);
            }
            $path = $request->file('qris_image')->store('qris', 'public');
            $validated['qris_image'] = $path;
        }

        $paymentSetting->update($validated);

        return redirect()
            ->route('admin.payment-settings.index')
            ->with('success', 'Metode pembayaran berhasil diupdate');
    }

    /**
     * Remove the specified payment setting
     */
    public function destroy(PaymentSetting $paymentSetting)
    {
        // Delete QRIS image if exists
        if ($paymentSetting->qris_image) {
            Storage::disk('public')->delete($paymentSetting->qris_image);
        }

        $paymentSetting->delete();

        return redirect()
            ->route('admin.payment-settings.index')
            ->with('success', 'Metode pembayaran berhasil dihapus');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(PaymentSetting $paymentSetting)
    {
        $paymentSetting->update([
            'is_active' => !$paymentSetting->is_active
        ]);

        return redirect()
            ->route('admin.payment-settings.index')
            ->with('success', 'Status metode pembayaran berhasil diubah');
    }
}
