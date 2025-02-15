<?php

namespace App\Http\Controllers\Lab\BillingFlow;

use \PDF;
use App\Http\Controllers\Controller;
use App\Models\BillingFlow;
use App\Models\Employee;
use App\Models\LabModel;
use App\Models\PatientAssignFlow;
use App\Models\PatientData;
use App\Models\Test;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BillingFlowController extends Controller
{
    
    public function viewPatientById(Request $request, $id) {

        try {

            // Fetch the patient from PatientData model
            $patient = PatientData::where('id', $id)->first(['name', 'phone', 'associated_user_id']);

            if (!$patient) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Patient not found',
                ], 404);
            }

            // Fetch tests and patient_card_id from PatientAssignFlow model
            $assignFlow = PatientAssignFlow::where('patient_id', $id)->first();
            
        
            // Fetch logged-in user and their lab ID
            $userData = $request->user();
            

           if ($userData && isset($userData->id)) {
               
                $labData = LabModel::where('user_id', $userData->id)->first();
           }
                        

            if (!$labData->id) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Lab not found for the given user ID.',
                ], 404);
            }

            // Fetch employees based on lab ID
            $employees = Employee::where('lab_id', $labData->id)->get();

            if ($employees->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No employees found for the given lab ID.',
                ], 404);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Patient details fetched successfully',
                'data' => [
                    'patient' => $patient,
                    'tests' => $assignFlow,
                    'patient_card_id' => $assignFlow->patient_card_id ?? null,
                    'employeeData' => $employees
                ],
            ]);
            

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }   

    
    public function submitBilling(Request $request) {

        $validated = $request->validate([
            'patient_id' => 'required|exists:patient_data,id',
            'associated_user_id' => 'required|exists:users,id',
            'selected_tests' => 'required|array|min:1',
            'final_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'selected_employee' => 'required',

        ]);

        try {
            DB::beginTransaction();

            // fetch lab id 
            $userData = $request->user();
            $lab = LabModel::where('user_id', $userData->id)->first();
            $labId = isset($lab) ? $lab->id : null;

            // Get patient assign flow
            $patientFlow = PatientAssignFlow::where('patient_id', $validated['patient_id'])->first();


            // Handle file upload
            if ($request->hasFile('file')) {
                // Ensure the directory exists
                if (!Storage::disk('public')->exists('patient_billing_files')) {
                    Storage::disk('public')->makeDirectory('patient_billing_files');
                }

                // Get original file name (sanitized)
                $file = $request->file('file');
                $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $filePath = "patient_billing_files/$fileName";


                // Store new file
                $file->storeAs('patient_billing_files', $fileName, 'public');
    
            }


            // Create billing record
            $billing = BillingFlow::create([
                'patient_id' => $validated['patient_id'],
                'associated_user_id' => $validated['associated_user_id'],
                'final_amount' => $validated['final_amount'],
                'discount' => $validated['discount'] ?? 0,
                'selected_employee_id' => $validated['selected_employee'],
                'patient_assign_flow_id' => $patientFlow ? $patientFlow->id : null,
                'tests' => $validated['selected_tests'],
                'lab_id' => $labId,
                'transaction_id' => 'SWASTHA-' . strtoupper(Str::random(3)) . rand(10000, 99999),
                'bill_file' => $filePath ?? null, // Save file path in database
            ]);


            // if the billing resource is successfully created 
            if ($patientFlow) {

                foreach ($validated['selected_tests'] as $testId) {
                    $this->removeSelectedTest($validated['patient_id'], $testId);
                }
                            
                $patientAssignData = $this->updateBillingStatus($patientFlow);

                if ($patientAssignData && empty($patientAssignData->tests)) {
                    $patientAssignData->billing_status = 'paid'; 
                    $patientAssignData->save(); // Save the changes
                }
            
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Billing stored successfully',
                'data' => $billing,
            ]);
            
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Error storing billing',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function removeSelectedTest($patientId, $selectedTestId) {
        // Fetch the patient assignment record
        $patientAssignFlow = PatientAssignFlow::where('patient_id', $patientId)->first();
    
        if (!$patientAssignFlow) {
            return response()->json(['message' => 'Patient record not found'], 404);
        }
    
        // Ensure tests is an array
        $tests = is_array($patientAssignFlow->tests) ? $patientAssignFlow->tests : json_decode($patientAssignFlow->tests, true);
    
        // Filter out the selected test
        $updatedTests = array_filter($tests, fn($test) => $test['id'] != $selectedTestId);
    
        // Update the tests column only if changes are made
        if (count($updatedTests) !== count($tests)) {
            $patientAssignFlow->tests = array_values($updatedTests); // Keep array indexes sequential
            $patientAssignFlow->save();
        }

        return response()->json(['message' => 'Test removed successfully', 'updated_tests' => $updatedTests]);

    }


    public function updateBillingStatus($patientFlow) {

       $data = PatientAssignFlow::where('patient_id', $patientFlow->patient_id)->first();
     
       return $data;
       
    }

    

    public function viewPaidPatients(Request $request) {
        $userData = $request->user();
    
        try {
            // Admin can see all paid patients
            if ($userData->role === 'admin') {
                $paidPatientIds = PatientAssignFlow::where('billing_status', 'paid')
                    ->pluck('patient_id')
                    ->unique();
                
                $paidPatients = PatientData::whereIn('id', $paidPatientIds)->get();

                return response()->json($paidPatients);
            }
    
            // Lab or Hospital can see their paid patients
            if (in_array($userData->role, ['lab', 'hospital'])) {
                $labId = LabModel::where('user_id', $userData->id)->value('id');
                if (!$labId) {
                    return response()->json(['error' => 'Lab not found'], 404);
                }
    
                $billingData = BillingFlow::where('lab_id', $labId)
                    ->whereHas('patientAssignFlow', function ($q) {
                        $q->where('billing_status', 'paid');
                    })->pluck('patient_id')->unique();
    
                $paidPatients = PatientData::whereIn('id', $billingData)->get();
                return response()->json($paidPatients);
            }
    
            // Users can see their paid patients (Check in BillingFlow)
            if ($userData->role === 'user') {

                $billingData = BillingFlow::where(function ($query) use ($userData) {
                    $query->where('associated_user_id', $userData->id);
                })->whereHas('patientAssignFlow', function ($q) {
                    $q->where('billing_status', 'paid');
                })->pluck('patient_id')->unique();

    
                $paidPatients = PatientData::whereIn('id', $billingData)->get();

                return response()->json($paidPatients);
            }
    
            return response()->json(['error' => 'Unauthorized role'], 403);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    public function searchPaidPatients(Request $request) {
        $query = $request->input('query');
        $user = $request->user(); // Get the currently authenticated user
    
        // Early return for empty queries
        if (empty($query)) {
            return response()->json(['results' => []]);
        }
    
        try {
            // Base query to fetch paid patients
            $patientsQuery = PatientAssignFlow::with('patientData')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('patient_card_id', 'like', "%{$query}%") // Filter by patient_card_id
                        ->orWhereHas('patientData', function ($subQuery) use ($query) {
                            $subQuery->where('name', 'like', "%{$query}%")
                                ->orWhere('phone', 'like', "%{$query}%");
                        });
                });
    
            // Apply restrictions if the role is doctor or worker
            if (in_array($user->role, ['doctor', 'worker'])) {
                $patientsQuery->whereHas('patientData', function ($subQuery) use ($user) {
                    $subQuery->where('associated_user_email', $user->email)
                             ->orWhere('associated_user_id', $user->id);
                });
            }
    
            // Apply restrictions if the role is lab or hospital
            if (in_array($user->role, ['lab', 'hospital'])) {
                // Fetch lab ID associated with the user
                $labData = LabModel::where('user_id', $user->id)->first();
    
                if (!$labData) {
                    return response()->json([
                        'status' => 403,
                        'message' => 'You do not have access to this data.',
                    ]);
                }
    
                $labId = $labData->id;
    
                // Ensure the patient has a paid record in the lab
                $patientsQuery->whereHas('billingFlow', function ($subQuery) use ($labId) {
                    $subQuery->where('lab_id', $labId);
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
    

    public function ViewPatientBillPdf(Request $request, $id) {
        try {
            // Get the authenticated user data
            $userData = $request->user();
    
            // Fetch lab_id based on user_id
            $labId = labModel::where('user_id', $userData->id)->value('id');
    
            // Fetch patient billing data
            $patientData = BillingFlow::where('patient_id', $id)
                ->where('lab_id', $labId)
                ->get()
                ->toArray();
    
            // Extract all test IDs
            $testIds = collect($patientData)->pluck('tests')->flatten()->unique()->toArray();
    
            // Fetch test names
            $testNames = Test::whereIn('id', $testIds)->pluck('name', 'id');
    
            // Attach test names to each bill entry
            foreach ($patientData as &$bill) {
                $bill['tests'] = array_map(fn($testId) => $testNames[$testId] ?? 'Unknown', $bill['tests']);
            }
    
            return response()->json($patientData);
    
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to fetch patient bill data.',
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    

    ////// working on pending patient api's 

    
    public function viewPendingPatients(Request $request) {
        $userData = $request->user();
    
        try {
            // Admin can see all pending patients
            if ($userData->role === 'admin') {
                $pendingPatientIds = PatientAssignFlow::where('billing_status', 'pending')
                    ->pluck('patient_id')
                    ->unique();
    
                $pendingPatients = PatientData::whereIn('id', $pendingPatientIds)->get();
    
                return response()->json($pendingPatients);
            }
    
            // Lab or Hospital can see their pending patients
            if (in_array($userData->role, ['lab', 'hospital'])) {
                $labId = LabModel::where('user_id', $userData->id)->value('id');
                if (!$labId) {
                    return response()->json(['error' => 'Lab not found'], 404);
                }
    
                $billingData = BillingFlow::where('lab_id', $labId)
                    ->whereHas('patientAssignFlow', function ($q) {
                        $q->where('billing_status', 'pending');
                    })->pluck('patient_id')->unique();
    
                $pendingPatients = PatientData::whereIn('id', $billingData)->get();
                return response()->json($pendingPatients);
            }
    
            // Users can see their pending patients (Check in BillingFlow)
            if ($userData->role === 'user') {
                $billingData = BillingFlow::where('associated_user_id', $userData->id)
                    ->whereHas('patientAssignFlow', function ($q) {
                        $q->where('billing_status', 'pending');
                    })->pluck('patient_id')->unique();
    
                $pendingPatients = PatientData::whereIn('id', $billingData)->get();
    
                return response()->json($pendingPatients);
            }
    
            return response()->json(['error' => 'Unauthorized role'], 403);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function searchPendingPatients(Request $request) {
        $query = $request->input('query');
        $user = $request->user(); // Get the currently authenticated user
    
        // Early return for empty queries
        if (empty($query)) {
            return response()->json(['results' => []]);
        }
    
        try {
            // Base query to fetch pending patients
            $patientsQuery = PatientAssignFlow::with('patientData')
                ->where('billing_status', 'pending')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('patient_card_id', 'like', "%{$query}%") // Filter by patient_card_id
                        ->orWhereHas('patientData', function ($subQuery) use ($query) {
                            $subQuery->where('name', 'like', "%{$query}%")
                                ->orWhere('phone', 'like', "%{$query}%");
                        });
                });
    
            // Apply restrictions if the role is doctor or worker
            if (in_array($user->role, ['doctor', 'worker'])) {
                $patientsQuery->whereHas('patientData', function ($subQuery) use ($user) {
                    $subQuery->where('associated_user_email', $user->email)
                             ->orWhere('associated_user_id', $user->id);
                });
            }
    
            // Apply restrictions if the role is lab or hospital
            if (in_array($user->role, ['lab', 'hospital'])) {
                // Fetch lab ID associated with the user
                $labData = LabModel::where('user_id', $user->id)->first();
    
                if (!$labData) {
                    return response()->json([
                        'status' => 403,
                        'message' => 'You do not have access to this data.',
                    ]);
                }
    
                $labId = $labData->id;
    
                // Ensure the patient has a pending record in the lab
                $patientsQuery->whereHas('billingFlow', function ($subQuery) use ($labId) {
                    $subQuery->where('lab_id', $labId);
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
    
    


    
    

  

   


}



// in patientAssignFlow model change the billing_status col if tests are empty in same model  