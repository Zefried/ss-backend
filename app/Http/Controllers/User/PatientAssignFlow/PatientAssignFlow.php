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

        try{

            // Validate the incoming request
            $validatedData = $request->validate([
                'patient_id' => 'required|exists:patient_data,id',
                'tests' => 'required|array',
            ]);

            // Store the data in the database
            $assignFlow = ModelsPatientAssignFlow::create([
                'patient_id' => $validatedData['patient_id'],
                'tests' => $validatedData['tests'],
            ]);

            return response()->json([
                'status' => 201,
                'message' => 'Test assigned successfully',
                'data' => $assignFlow
            ]);

        }catch(Exception $e){

            return response()->json([
                'status' => 500,
                'message' => 'fatal error',
                'error' => $e->getMessage(),
            ]);
        }
        
    }

    public function viewAssignedTest(){
        try {
            // Fetch all patients with their assigned tests
            $patients = ModelsPatientAssignFlow::with('patientData')->get();

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
