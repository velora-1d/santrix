<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\Kelas;
use PDF;

class KartuDigitalController extends Controller
{
    public function index(Request $request)
    {
        $pesantrenId = auth()->user()->pesantren_id;
        $query = Santri::where('pesantren_id', $pesantrenId); // SCOPED

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_santri', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Filter Kelas
        if ($request->has('kelas_id') && $request->kelas_id != '') {
            $query->where('kelas_id', $request->kelas_id);
        }

        $santris = $query->where('is_active', true)
                        ->orderBy('nama_santri', 'asc')
                        ->paginate(20);

        // Get lists for filters (borrowed logic from SekretarisController if needed, or simple query)
        $kelasList = Kelas::where('pesantren_id', $pesantrenId)->get();
        
        return view('sekretaris.kartu-digital.index', compact('santris', 'kelasList'));
    }

    public function downloadPdf($subdomain, $id)
    {
        $pesantrenId = auth()->user()->pesantren_id;
        $santri = Santri::where('pesantren_id', $pesantrenId)->findOrFail($id);

        // Generate PDF
        // Using 'portrait' ID Card size is roughly 85.6mm x 53.98mm. 
        // Or simple A4? User wants "Kartu". Let's try to set custom paper size if possible, or standard A4 with card centered.
        // For simplicity and printability, A4 with card graphic is safer. Or custom sleek rectangle.
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('sekretaris.kartu-digital.pdf', compact('santri'));
        
        // Horizontal Card (CR-80 size equivalent scaling)
        // 53.98mm x 85.60mm
        $pdf->setPaper('A4', 'portrait'); 

        return $pdf->download('Kartu-Syahriah-' . $santri->nis . '.pdf');
    }

    public function previewPdf($subdomain, $id)
    {
        $pesantrenId = auth()->user()->pesantren_id;
        $santri = Santri::where('pesantren_id', $pesantrenId)->findOrFail($id);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('sekretaris.kartu-digital.pdf', compact('santri'));
        
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Kartu-Syahriah-' . $santri->nis . '.pdf');
    }
}
