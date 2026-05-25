<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TriageSession;

class TriageController extends Controller
{
    /**
     * Fetch all logs from MySQL and render the main interactive application.
     */
    public function index()
    {
        // Query database records sorted by most recent first
        $studentRecords = TriageSession::orderBy('created_at', 'desc')->get();

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

        try {
            // Use Eloquent model to create record
            $session = TriageSession::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Telemetry record successfully written to database.',
                'id' => $session->id
            ], 201);
        } catch (\Exception $e) {
            // Log and return a safe error message for clients
            logger()->error('TriageSession create failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to write telemetry record.'
            ], 500);
        }
    }
}
