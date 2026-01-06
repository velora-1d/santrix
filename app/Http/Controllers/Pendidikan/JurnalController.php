<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JurnalKbm;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
{
    /**
     * List of Jurnal KBM
     */
    public function index(Request $request)
    {
        $query = JurnalKbm::with(['kelas', 'mapel', 'user'])
            ->where('pesantren_id', Auth::user()->pesantren_id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');

        // Simple Filter
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $jurnals = $query->paginate(10);
        
        // Data for filters
        $kelas_list = \App\Models\Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('pendidikan.jurnal.index', compact('jurnals', 'kelas_list'));
    }

    /**
     * Show Detail
     */
    public function show($id)
    {
        $jurnal = JurnalKbm::with(['details.santri', 'kelas', 'mapel', 'user'])->findOrFail($id);
        
        return view('pendidikan.jurnal.show', compact('jurnal'));
    }
}
