<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    /**
     * Tampilkan daftar pengaturan rekening & e-wallet
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = PaymentSetting::query();

        if ($search) {
            $query->where('bank_name', 'like', "%{$search}%")
                  ->orWhere('bank_code', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('account_holder', 'like', "%{$search}%");
        }

        $paymentSettings = $query->orderBy('category')->orderBy('bank_name')->get();

        return view('BACKEND.payment_settings.index', compact('paymentSettings', 'search'));
    }

    /**
     * Simpan rekening / e-wallet baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_code' => 'required|string|max:20|unique:payment_settings,bank_code',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'account_holder' => 'required|string|max:255',
            'category' => 'required|in:bank,ewallet',
        ], [
            'bank_code.unique' => 'Kode Bank / E-Wallet sudah digunakan.',
            'bank_code.required' => 'Kode Bank wajib diisi.',
            'bank_name.required' => 'Nama Bank / E-Wallet wajib diisi.',
            'account_number.required' => 'Nomor Rekening / Virtual Account wajib diisi.',
            'account_holder.required' => 'Nama Pemilik Rekening wajib diisi.',
        ]);

        PaymentSetting::create([
            'bank_code' => strtoupper($request->bank_code),
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'category' => $request->category,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.payment-settings.index')->with('success', 'Rekening pembayaran berhasil ditambahkan.');
    }

    /**
     * Update data rekening / e-wallet
     */
    public function update(Request $request, $id)
    {
        $setting = PaymentSetting::findOrFail($id);

        $request->validate([
            'bank_code' => 'required|string|max:20|unique:payment_settings,bank_code,' . $setting->id,
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'account_holder' => 'required|string|max:255',
            'category' => 'required|in:bank,ewallet',
        ], [
            'bank_code.unique' => 'Kode Bank / E-Wallet sudah digunakan.',
            'bank_code.required' => 'Kode Bank wajib diisi.',
            'bank_name.required' => 'Nama Bank / E-Wallet wajib diisi.',
            'account_number.required' => 'Nomor Rekening / Virtual Account wajib diisi.',
            'account_holder.required' => 'Nama Pemilik Rekening wajib diisi.',
        ]);

        $setting->update([
            'bank_code' => strtoupper($request->bank_code),
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'category' => $request->category,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.payment-settings.index')->with('success', 'Rekening pembayaran berhasil diperbarui.');
    }

    /**
     * Toggle status aktif / nonaktif
     */
    public function toggleStatus($id)
    {
        $setting = PaymentSetting::findOrFail($id);
        $setting->is_active = !$setting->is_active;
        $setting->save();

        $statusText = $setting->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.payment-settings.index')->with('success', "Metode pembayaran {$setting->bank_name} berhasil {$statusText}.");
    }

    /**
     * Hapus rekening
     */
    public function destroy($id)
    {
        $setting = PaymentSetting::findOrFail($id);
        $setting->delete();

        return redirect()->route('admin.payment-settings.index')->with('success', 'Rekening pembayaran berhasil dihapus.');
    }
}
