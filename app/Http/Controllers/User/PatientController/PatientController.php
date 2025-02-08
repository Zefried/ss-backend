<?php

namespace App\Http\Controllers\User\PatientController;

use App\Http\Controllers\Controller;
use App\Models\Patient_location_Count;
use App\Models\PatientData;
use App\Models\PatientLocation;
use App\Models\PatientLocationCount;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{

    public function addPatient(Request $request) {

        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:patient_data,phone',
            'email' => 'email|unique:patient_data,email',
            'patient_location_id' => 'required',
        ]);
    
        if($validator->fails()){
            return response()->json(['validation_error' => $validator->messages()]);
        }
    
        try {
            $userData = $request->user();
    
            $patientRequestData = PatientData::create([
                'name' => $request->input('name'),
                'patient_location_id' => $request->input('patient_location_id'),
                'age' => $request->input('age'),
                'sex' => $request->input('sex'),
                'relativeName' => $request->input('relativeName'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'identityProof' => $request->input('identityProof'),
                'village' => $request->input('village'),
                'po' => $request->input('po'),
                'ps' => $request->input('ps'),
                'pin' => $request->input('pin'),
                'district' => $request->input('district'),
                'state' => $request->input('state'),
                'request_status' => 'pending',
                'associated_user_email' => $userData->email,
                'associated_user_id' => $userData->id,
            ]);
    
                $newPatientCardId = $this->generatePatientCardId($patientRequestData->patient_location_id, $userData);
       
            
                $this->createPatientLocationCount($patientRequestData, $userData->id, $newPatientCardId);


                return response()->json([
                    'status' => 201,
                    'message' => 'New patient data and card id created successfully.',
                    'patient_card_id' => $newPatientCardId,
                ]);

    
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function generatePatientCardId($locationId, $userData) {

        $location = PatientLocation::find($locationId);

        if ($location) {
            $locationAbbreviation = strtoupper(substr($location->location_name, 0, 3));
            $locationCount = str_pad(PatientLocationCount::where('location_id', $locationId)->count() + 1, 2, '0', STR_PAD_LEFT);
            $formattedUserId = str_pad($userData->id, 2, '0', STR_PAD_LEFT);
    
            return $locationAbbreviation . '-' . $locationCount . '-' . $formattedUserId;
        }
        throw new \Exception("Location not found.");
    }
    
    private function createPatientLocationCount($patientData, $userId, $patientCardId) {

        return PatientLocationCount::create([
            'location_id' => $patientData->patient_location_id,
            'patient_id' => $patientData->id,
            'associated_user_id' => $userId,
            'patient_card_id' => $patientCardId,
        ]);

    }
    
    public function viewPatient(Request $request)
    {
        $user = $request->user();
        
        $query = PatientData::where('disable_status', 0);
    
        if ($user->role !== 'admin') {
            $query->where('associated_user_email', $user->email);
        }

        $recordsPerPage = $request->input('recordsPerPage', 10);  // Set default to 10
        $page = $request->input('page', 1);
    
    
        try {
            $patientFetchedData = $query->paginate($recordsPerPage, ['*'], 'page', $page);
    
            return response()->json([
                'status' => 200,
                'listData' => $patientFetchedData->items(),  // No need for null check
                'total' => $patientFetchedData->total(),    // No need for null check
                'message' => 'Data fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to fetch data',
                'error' => $e->getMessage(),  // Provides error message for debugging
            ]);
        }
    }

    public function searchPatients(Request $request) {
        $query = $request->input('query');
        $user = $request->user(); // Get the currently authenticated user
    
        // Early return for empty queries
        if (empty($query)) {
            return response()->json(['results' => []]);
        }
    
        try {
            // Base query to fetch patients
            $patientsQuery = PatientData::where('disable_status', '!=', '1')
                ->where(function ($subQuery) use ($query) {
                    $subQuery->where('name', 'like', '%' . $query . '%')
                             ->orWhere('phone', 'like', '%' . $query . '%');
                });
    
            // If its user then, restrict results to their associated records
            if ($user->role == 'user') {
                $patientsQuery->where(function ($subQuery) use ($user) {
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
        
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Database error: ' . $e->getMessage(),
                'message' => 'There was an issue with the search. Please try again.',
            ]);
        }
    }
    
    

    Public function viewPatientCard($id){
        try{
            
            $patientData = PatientLocationCount::where('patient_id', $id)
            ->with('patientData')->get();
        
            return response()->json([
            'status' => 200,
            'patientCountData' => $patientData,
            ]);

        }catch(Exception $e){
            
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong please check network console',
                'error' => $e->getMessage()
            ]);
        }   
    }
    

}
