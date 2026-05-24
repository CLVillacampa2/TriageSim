<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TriageController extends Controller
{
    /**
     * Fetch all logs from MySQL and render the main interactive application.
     */
    public function index()
    {
        // Query database records sorted by most recent first
        $studentRecords = DB::table('triage_sessions')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('triagesim', compact('studentRecords'));
    }

    /**
     * Receive completed student simulator logs and save them directly to MySQL.
     */
    public function storeSession(Request $request)
    {
        // Validate incoming payload parameters
        $validated = $request->validate([
            'student_id' => 'required|string|max:255',
            'student_name' => 'required|string|max:255',
            'cohort' => 'required|string|max:255',
            'scenario' => 'required|string|max:255',
            'latency' => 'required|numeric',
            'efficiency' => 'required|integer',
            'accuracy' => 'required|integer'
        ]);

        // Securely write records into MySQL database table
        DB::table('triage_sessions')->insert(array_merge($validated, [
            'created_at' => now(),
            'updated_at' => now()
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Telemetry record successfully written to database.'
        ], 200);
    }
}
