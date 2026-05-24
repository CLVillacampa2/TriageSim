<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TriageController extends Controller
{
    // Load the main app and pass the database records to the dashboard
    public function index()
    {
        // Fetch all student sessions from the database
        $studentRecords = DB::table('triage_sessions')->orderBy('created_at', 'desc')->get();
        
        return view('triagesim', compact('studentRecords'));
    }

    // Save a new completed simulation session via AJAX
    public function storeSession(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'student_name' => 'required|string',
            'cohort' => 'required|string',
            'scenario' => 'required|string',
            'latency' => 'required|numeric',
            'efficiency' => 'required|integer',
            'accuracy' => 'required|integer'
        ]);

        DB::table('triage_sessions')->insert(array_merge($validated, [
            'created_at' => now(),
            'updated_at' => now()
        ]));

        return response()->json(['status' => 'success']);
    }
}
