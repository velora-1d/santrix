<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Display dashboard page
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Display settings page
     */
    public function pengaturan()
    {
        // Only show users from the SAME pesantren
        $users = User::where('pesantren_id', Auth::user()->pesantren_id)
                     ->orderBy('created_at', 'desc')
                     ->get();
                     
        // Fetch Kelas and Asrama for the current tenant
        $kelas_list = \App\Models\Kelas::withCount('santri')->orderBy('tingkat')->orderBy('nama_kelas')->get();
        $asrama_list = \App\Models\Asrama::withCount(['santri', 'kobong'])->get();
        $tahun_ajaran_list = \App\Models\TahunAjaran::orderBy('is_active', 'desc')->orderBy('nama', 'desc')->get();
        
        return view('admin.pengaturan', compact('users', 'kelas_list', 'asrama_list', 'tahun_ajaran_list'));
    }

    /**
     * Update application settings
     */
    public function updateAppSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'app_contact' => 'nullable|string|max:255',
            'app_email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Store in session for now (can be moved to database later)
        session([
            'app_name' => $request->app_name,
            'app_contact' => $request->app_contact,
            'app_email' => $request->app_email,
        ]);

        return redirect()->route('admin.pengaturan')->with('success', 'Pengaturan aplikasi berhasil diperbarui!');
    }

    /**
     * Create new user
     */
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin_pusat,sekretaris,bendahara,pendidikan',
        ], [
            'password.min' => 'Password minimal harus 8 karakter.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'name.required' => 'Nama wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('tab', 'users');
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'pesantren_id' => Auth::user()->pesantren_id,
            ]);

            return redirect()->route('admin.pengaturan')->with('success', 'User berhasil ditambahkan!')->with('tab', 'users');
        } catch (\Exception $e) {
            Log::error('Gagal membuat user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput()->with('tab', 'users');
        }
    }

    /**
     * Update existing user
     */
    public function updateUser(Request $request, $tenant, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin_pusat,sekretaris,bendahara,pendidikan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('tab', 'users');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return redirect()->route('admin.pengaturan')->with('success', 'User berhasil diperbarui!')->with('tab', 'users');
    }

    /**
     * Delete user
     */
    public function deleteUser($tenant, $id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri!')->with('tab', 'users');
        }

        $user->delete();

        return redirect()->route('admin.pengaturan')->with('success', 'User berhasil dihapus!')->with('tab', 'users');
    }

    // ==================== KELAS MANAGEMENT ====================

    public function storeKelas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'tahun_ajaran' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('tab', 'kelas-asrama');
        }

        \App\Models\Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $request->tingkat,
            'kapasitas' => $request->kapasitas,
            'tahun_ajaran' => $request->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1),
            // Default level to 0 or derive from tingkat if possible, but keep simple for now
            'level' => 0, 
        ]);

        return redirect()->route('admin.pengaturan')->with('success', 'Kelas berhasil ditambahkan!')->with('tab', 'kelas-asrama');
    }

    public function updateKelas(Request $request, $tenant, $id)
    {
        $kelas = \App\Models\Kelas::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('tab', 'kelas-asrama');
        }

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $request->tingkat,
            'kapasitas' => $request->kapasitas,
        ]);

        return redirect()->route('admin.pengaturan')->with('success', 'Kelas berhasil diperbarui!')->with('tab', 'kelas-asrama');
    }

    public function deleteKelas($tenant, $id)
    {
        $kelas = \App\Models\Kelas::findOrFail($id);
        
        if ($kelas->santri()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus kelas. Masih ada santri di kelas ini.')->with('tab', 'kelas-asrama');
        }

        $kelas->delete();

        return redirect()->route('admin.pengaturan')->with('success', 'Kelas berhasil dihapus!')->with('tab', 'kelas-asrama');
    }

    // ==================== ASRAMA MANAGEMENT ====================

    public function storeAsrama(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_asrama' => 'required|string|max:255',
            'gender' => 'required|in:putra,putri',
            'jumlah_kamar' => 'required|integer|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('tab', 'kelas-asrama');
        }

        $asrama = \App\Models\Asrama::create([
            'nama_asrama' => $request->nama_asrama,
            'gender' => $request->gender,
        ]);

        // Auto-generate Kobong (Kamar)
        for ($i = 1; $i <= $request->jumlah_kamar; $i++) {
            \App\Models\Kobong::create([
                'asrama_id' => $asrama->id,
                'nama_kobong' => 'Kamar ' . $i,
                'kapasitas' => 20, // Default capacity
            ]);
        }

        return redirect()->route('admin.pengaturan')->with('success', 'Asrama dan kamar berhasil dibuat!')->with('tab', 'kelas-asrama');
    }

    public function updateAsrama(Request $request, $tenant, $id)
    {
        $asrama = \App\Models\Asrama::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_asrama' => 'required|string|max:255',
            'gender' => 'required|in:putra,putri',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('tab', 'kelas-asrama');
        }

        $asrama->update([
            'nama_asrama' => $request->nama_asrama,
            'gender' => $request->gender,
        ]);

        return redirect()->route('admin.pengaturan')->with('success', 'Asrama berhasil diperbarui!')->with('tab', 'kelas-asrama');
    }

    public function deleteAsrama($tenant, $id)
    {
        $asrama = \App\Models\Asrama::findOrFail($id);
        
        // Check if any santri is assigned to this asrama
        if ($asrama->santri()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus asrama. Masih ada santri di asrama ini.')->with('tab', 'kelas-asrama');
        }

        // Check if any kobong has santri (if santri linked to kobong too)
        // Assuming santri -> asrama + kobong check:
        // Delete all kobong first
        $asrama->kobong()->delete();
        $asrama->delete();

        return redirect()->route('admin.pengaturan')->with('success', 'Asrama berhasil dihapus!')->with('tab', 'kelas-asrama');
    }

    public function storeKobong(Request $request, $tenant, $asrama_id)
    {
        $asrama = \App\Models\Asrama::findOrFail($asrama_id);

        $validator = Validator::make($request->all(), [
            'nama_kobong' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('tab', 'kelas-asrama');
        }

        \App\Models\Kobong::create([
            'asrama_id' => $asrama->id,
            'nama_kobong' => $request->nama_kobong,
            'kapasitas' => $request->kapasitas,
        ]);

        return redirect()->route('admin.pengaturan')->with('success', 'Kamar berhasil ditambahkan!')->with('tab', 'kelas-asrama');
    }

    public function deleteKobong($tenant, $id)
    {
        $kobong = \App\Models\Kobong::findOrFail($id);
        
        // Check if santri linked to this kobong
        // Assuming Santri model has kobong_id
        if (\App\Models\Santri::where('kobong_id', $id)->count() > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus kamar. Masih ada santri di kamar ini.')->with('tab', 'kelas-asrama');
        }

        $kobong->delete();

        return redirect()->route('admin.pengaturan')->with('success', 'Kamar berhasil dihapus!')->with('tab', 'kelas-asrama');
    }

    // ==================== TAHUN AJARAN MANAGEMENT ====================

    public function storeTahunAjaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255', // e.g. 2024/2025
            'semester' => 'required|string|max:50', // e.g. Ganjil/Genap
            'nominal_syahriah' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('tab', 'akademik');
        }

        \App\Models\TahunAjaran::create([
            'pesantren_id' => Auth::user()->pesantren_id,
            'nama' => $request->nama,
            'semester' => $request->semester,
            'nominal_syahriah' => $request->nominal_syahriah,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active' => false, // Default inactive, must be activated manually
        ]);

        return redirect()->route('admin.pengaturan')->with('success', 'Tahun Ajaran berhasil ditambahkan!')->with('tab', 'akademik');
    }

    public function updateTahunAjaran(Request $request, $tenant, $id)
    {
        $tahunAjaran = \App\Models\TahunAjaran::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'semester' => 'required|string|max:50',
            'nominal_syahriah' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('tab', 'akademik');
        }

        $tahunAjaran->update([
            'nama' => $request->nama,
            'semester' => $request->semester,
            'nominal_syahriah' => $request->nominal_syahriah,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->route('admin.pengaturan')->with('success', 'Tahun Ajaran berhasil diperbarui!')->with('tab', 'akademik');
    }

    public function activateTahunAjaran($tenant, $id)
    {
        $tahunAjaran = \App\Models\TahunAjaran::findOrFail($id);
        
        // Deactivate all others first
        \App\Models\TahunAjaran::where('pesantren_id', Auth::user()->pesantren_id)
            ->update(['is_active' => false]);
            
        // Activate selected
        $tahunAjaran->update(['is_active' => true]);

        return redirect()->route('admin.pengaturan')->with('success', 'Tahun Ajaran ' . $tahunAjaran->nama . ' ' . $tahunAjaran->semester . ' diaktifkan!')->with('tab', 'akademik');
    }

    public function deleteTahunAjaran($tenant, $id)
    {
        $tahunAjaran = \App\Models\TahunAjaran::findOrFail($id);
        
        if ($tahunAjaran->is_active) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus Tahun Ajaran yang sedang aktif!')->with('tab', 'akademik');
        }

        $tahunAjaran->delete();

        return redirect()->route('admin.pengaturan')->with('success', 'Tahun Ajaran berhasil dihapus!')->with('tab', 'akademik');
    }
}
