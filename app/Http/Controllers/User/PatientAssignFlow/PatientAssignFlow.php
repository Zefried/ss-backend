<?php

namespace App\Http\Controllers\User\PatientAssignFlow;

use App\Http\Controllers\Controller;
use App\Models\PatientAssignFlow as ModelsPatientAssignFlow;
use App\Models\PatientData;
use App\Models\PatientLocationCount;
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

    public function assigningTest(Request $request) {
        try {

            // Validate the incoming request
            $validatedData = $request->validate([
                'patient_id' => 'required|exists:patient_data,id',
                'tests' => 'required|array',
                'discount' => 'nullable|numeric|min:0|max:100', // Allow discount from 0 to 100
            ]);
    
            // Checking if patient exist Fetch
            $patientLocation = PatientLocationCount::where('patient_id', $validatedData['patient_id'])->first();
    
            // If patient is found, get the patient_card_id
            $patientCardId = $patientLocation ? $patientLocation->patient_card_id : null;
    
            // Check if a record with this patient_id already exists
            $assignFlow = ModelsPatientAssignFlow::where('patient_id', $validatedData['patient_id'])->first();
    
            if ($assignFlow) {
                // If record exists, increment visit_count by 1
                $assignFlow->visit_count += 1;
                $assignFlow->tests = $validatedData['tests']; // Update the tests
                $assignFlow->discount = $validatedData['discount'] ?? 0; // Update discount field
                $assignFlow->patient_card_id = $patientCardId; // Update patient_card_id
                $assignFlow->billing_status = 'pending';
                $assignFlow->save();
            } else {
                // If it's a new record, create a new entry with visit_count set to 1
                $assignFlow = ModelsPatientAssignFlow::create([
                    'patient_id' => $validatedData['patient_id'],
                    'tests' => $validatedData['tests'],
                    'discount' => $validatedData['discount'] ?? 0, // Default discount to 0 if not provided
                    'visit_count' => 1,  // Set visit_count to 1 for new records
                    'patient_card_id' => $patientCardId, // Set patient_card_id
                ]);
            }
    
            return response()->json([
                'status' => 201,
                'message' => 'Test assigned successfully',
                'data' => $assignFlow
            ]);
    
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error',
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    

    public function viewAssignedPatients(Request $request) {

        try {
            $user = $request->user();
            $patientsQuery = ModelsPatientAssignFlow::with('patientData');
        
            // For non-admin users, filter by associated patientData
            if ($user->role !== 'admin') {
                $patientsQuery->whereHas('patientData', function($query) use ($user) {
                    $query->where(function($subQuery) use ($user) {
                        $subQuery->where('associated_user_email', $user->email)
                                 ->orWhere('associated_user_id', $user->id);
                    });
                });
            }
        
            // Filter for pending billing_status directly in ModelsPatientAssignFlow
            $patientsQuery->where('billing_status', 'pending');
            
            $patients = $patientsQuery->get();
        
            if ($patients->isEmpty()) {
                return response()->json([
                    'status' => 403,
                    'message' => 'No assigned patient data found | May be all paid',
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
    

    public function searchAssignedPatient(Request $request) {
        $query = $request->input('query');
        $user = $request->user(); // Get the currently authenticated user
    
        // Early return for empty queries
        if (empty($query)) {
            return response()->json(['results' => []]);
        }
    
        try {
            // Base query to fetch assigned patients
            $patientsQuery = ModelsPatientAssignFlow::with('patientData')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('patient_card_id', 'like', '%' . $query . '%') // Filter by patient_card_id
                    ->orWhereHas('patientData', function ($subQuery) use ($query) {
                        $subQuery->where('name', 'like', '%' . $query . '%')
                            ->orWhere('phone', 'like', '%' . $query . '%');
                    });
            });

        
    
            // Apply restrictions if the role is doctor or worker
            if (in_array($user->role === 'user', ['doctor', 'worker'])) {
                $patientsQuery->whereHas('patientData', function ($subQuery) use ($user) {
                    $subQuery->where('associated_user_email', $user->email)
                             ->orWhere('associated_user_id', $user->id);
                });
            }
    
            $results = $patientsQuery->take(10)->get(); // Limit results to 10 for efficiency
    
            // If no results are found, return a message
            if ($results->isEmpty()) {
                return response()->json([
                    'status' => 403,
                    'message' => 'You cannot view patients that do not belong to you.',
                ]);
            }
    
            return response()->json([
                'status' => 200,
                'results' => $results,
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function checkVisit(){
        return response()->json([
            'status' => 200,
            'message' => 'Visit checked successfully',
        ]);
    }
    



}
