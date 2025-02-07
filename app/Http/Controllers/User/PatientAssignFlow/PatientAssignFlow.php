<?php

namespace App\Http\Controllers\User\PatientAssignFlow;

use App\Http\Controllers\Controller;
use App\Models\PatientAssignFlow as ModelsPatientAssignFlow;
use App\Models\PatientData;
use App\Models\Test;
use Exception;
use Illuminate\Http\Request;

class PatientAssignFlow extends Controller
{

    public function searchingTest(request $request){

        $query = $request->input('query');
        
   
        if (empty($query)) {
            return response()->json(['suggestions' => []]);
        }


        $suggestions = Test::where('disable_status', '!=', '1')
        ->where(function($subQuery) use ($request) {
            $searchQuery = $request->input('query');
            $subQuery->where('name', 'like', '%' . $searchQuery . '%')
                    ->orWhere('id', 'like', '%' . $searchQuery . '%');
        })
        ->take(10) 
        ->get(['name','id']);

        return response()->json(['suggestions' => $suggestions]);
    }

    public function assigningTest(Request $request){
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'patient_id' => 'required|exists:patient_data,id',
                'tests' => 'required|array',
            ]);

            // Check if a record with this patient_id already exists
            $assignFlow = ModelsPatientAssignFlow::updateOrCreate(
                ['patient_id' => $validatedData['patient_id']], // Check only by patient_id
                ['tests' => $validatedData['tests']]            // Update or create the tests field
            );

            return response()->json([
                'status' => 201,
                'message' => 'Test assigned successfully',
                'data' => $assignFlow
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'fatal error',
                'error' => $e->getMessage(),
            ]);
        }
    }


    public function viewAssignedTest(Request $request) {
        try {
            $user = $request->user();  // Get the currently authenticated user
    
            // Fetch all patients with their assigned tests where the associated email or user ID matches the authenticated user
            $patients = ModelsPatientAssignFlow::with('patientData')
                ->whereHas('patientData', function ($query) use ($user) {
                    $query->where(function ($subQuery) use ($user) {
                        $subQuery->where('associated_user_email', $user->email)
                                 ->orWhere('associated_user_id', $user->id);
                    });
                })
                ->get();
    
            // If no data found, return a message
            if ($patients->isEmpty()) {
                return response()->json([
                    'status' => 403,
                    'message' => 'You cannot view tests assigned to patients that do not belong to you.',
                ]);
            }
    
            return response()->json([
                'status' => 200,
                'message' => 'Data fetched successfully',
                'data' => $patients,
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error',
                'error' => $e->getMessage(),
            ]);
        }
    }
    



}
