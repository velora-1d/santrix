<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pesantren;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;

class PesantrenSettingsController extends Controller
{
    /**
     * Show pesantren settings form
     */
    public function index()
    {
        $pesantren = app('tenant');
        
        if (!$pesantren) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Pesantren tidak ditemukan.');
        }
        
        return view('admin.settings.pesantren', compact('pesantren'));
    }

    /**
     * Update pesantren information
     */
    public function update(Request $request)
    {
        $pesantren = app('tenant');
        
        if (!$pesantren) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Pesantren tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'kota' => 'nullable|string|max:100',
            'pimpinan_nama' => 'nullable|string|max:255',
            'pimpinan_jabatan' => 'nullable|string|max:100',
        ]);

        $pesantren->update($validated);

        return redirect()->route('admin.pengaturan')
            ->with('success', 'Informasi pesantren berhasil diperbarui!');
    }

    /**
     * Upload logo
     */
    public function uploadLogo(Request $request)
    {
        $pesantren = app('tenant');
        
        if (!$pesantren) {
            return response()->json(['error' => 'Pesantren tidak ditemukan.'], 404);
        }

        $request->validate([
            'logo' => 'required|image|mimes:jpg,jpeg,png,heic,webp,gif|max:2048', // 2MB max
        ]);

        try {
            // Delete old logo if exists
            if ($pesantren->logo_path && Storage::disk('public')->exists($pesantren->logo_path)) {
                Storage::disk('public')->delete($pesantren->logo_path);
            }

            // Store new logo
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("logos/{$pesantren->id}", $filename, 'public');

            // Update pesantren
            $pesantren->update(['logo_path' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Logo berhasil diupload!',
                'logo_url' => Storage::url($path),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengupload logo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload signature (TTD)
     */
    public function uploadSignature(Request $request)
    {
        $pesantren = app('tenant');
        
        if (!$pesantren) {
            return response()->json(['error' => 'Pesantren tidak ditemukan.'], 404);
        }

        $request->validate([
            'signature' => 'required|image|mimes:jpg,jpeg,png,heic,webp,gif|max:1024', // 1MB max
        ]);

        try {
            // Delete old signature if exists
            if ($pesantren->pimpinan_ttd_path && Storage::disk('public')->exists($pesantren->pimpinan_ttd_path)) {
                Storage::disk('public')->delete($pesantren->pimpinan_ttd_path);
            }

            // Store new signature
            $file = $request->file('signature');
            $filename = 'ttd_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("logos/{$pesantren->id}", $filename, 'public');

            // Update pesantren
            $pesantren->update(['pimpinan_ttd_path' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Tanda tangan berhasil diupload!',
                'signature_url' => Storage::url($path),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengupload tanda tangan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete logo
     */
    public function deleteLogo()
    {
        $pesantren = app('tenant');
        
        if (!$pesantren) {
            return response()->json(['error' => 'Pesantren tidak ditemukan.'], 404);
        }

        try {
            if ($pesantren->logo_path && Storage::disk('public')->exists($pesantren->logo_path)) {
                Storage::disk('public')->delete($pesantren->logo_path);
            }

            $pesantren->update(['logo_path' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Logo berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus logo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload logo pendidikan (optional)
     */
    public function uploadLogoPendidikan(Request $request)
    {
        $pesantren = app('tenant');
        
        if (!$pesantren) {
            return response()->json(['error' => 'Pesantren tidak ditemukan.'], 404);
        }

        $request->validate([
            'logo_pendidikan' => 'required|image|mimes:jpg,jpeg,png,heic,webp,gif|max:2048',
        ]);

        try {
            // Delete old logo if exists
            if ($pesantren->logo_pendidikan_path && Storage::disk('public')->exists($pesantren->logo_pendidikan_path)) {
                Storage::disk('public')->delete($pesantren->logo_pendidikan_path);
            }

            // Store new logo
            $file = $request->file('logo_pendidikan');
            $filename = 'logo_pendidikan_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("logos/{$pesantren->id}", $filename, 'public');

            // Update pesantren
            $pesantren->update(['logo_pendidikan_path' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Logo pendidikan berhasil diupload!',
                'logo_url' => Storage::url($path),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengupload logo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete logo pendidikan
     */
    public function deleteLogoPendidikan()
    {
        $pesantren = app('tenant');
        
        if (!$pesantren) {
            return response()->json(['error' => 'Pesantren tidak ditemukan.'], 404);
        }

        try {
            if ($pesantren->logo_pendidikan_path && Storage::disk('public')->exists($pesantren->logo_pendidikan_path)) {
                Storage::disk('public')->delete($pesantren->logo_pendidikan_path);
            }

            $pesantren->update(['logo_pendidikan_path' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Logo pendidikan berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus logo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show branding settings form (logo, signature, colors)
     */
    public function branding()
    {
        $pesantren = app('tenant');
        return view('admin.settings.pesantren', compact('pesantren'));
    }

    /**
     * Update branding settings
     */
    public function updateBranding(Request $request)
    {
        $pesantren = app('tenant');
        
        $validated = $request->validate([
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
        ]);

        $pesantren->update($validated);

        return back()->with('success', 'Branding berhasil diperbarui!');
    }
}
