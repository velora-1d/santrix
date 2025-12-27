<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    /**
     * Download the database backup (SQLite file).
     */
    public function download()
    {
        $dbPath = database_path('database.sqlite');

        if (!File::exists($dbPath)) {
            return back()->with('error', 'Database file not found.');
        }

        $filename = 'backup_' . date('Y-m-d_H-i-s') . '_riyadlul_huda.sqlite';

        return Response::download($dbPath, $filename, [
            'Content-Type' => 'application/x-sqlite3',
        ]);
    }
}
