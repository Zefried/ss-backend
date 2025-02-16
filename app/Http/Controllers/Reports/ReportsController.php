<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\BillingFlow;
use App\Models\DoctorAndWorker;
use App\Models\Employee;
use App\Models\LabModel;
use App\Models\PatientAssignFlow;
use App\Models\PatientData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportsController extends Controller
{
    ///////// LAB REPORTS SECTION STARTS HERE ///////////
    ///////// LAB REPORTS SECTION STARTS HERE ///////////
    ///////// LAB REPORTS SECTION STARTS HERE ///////////

            public function getBillingCount(Request $request) {

                try {
                    // Get the authenticated user
                    $userData = $request->user();

                    // Fetch the lab ID associated with the user
                    $lab = LabModel::where('user_id', $userData->id)->first();

                    if (!$lab) {
                        return response()->json([
                            'status' => 404,
                            'message' => 'Lab not found for the user',
                        ]);
                    }

                    // Fetch the total billing count for the lab
                    $totalBillCount = BillingFlow::where('lab_id', $lab->id)->count();

                    return response()->json([
                        'status' => 200,
                        'total' => $totalBillCount,
                    ]);

                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Fatal error',
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            
            public function getFilteredBillingCount(Request $request) {
                try {
                    // Get the authenticated user
                    $userData = $request->user();

                    // Fetch the lab ID associated with the user
                    $lab = LabModel::where('user_id', $userData->id)->first();

                    if (!$lab) {
                        return response()->json([
                            'status' => 404,
                            'message' => 'Lab not found for the user',
                        ]);
                    }

                    // Get the date range from the request
                    $startDate = $request->query('start_date');
                    $endDate = $request->query('end_date');

                    // Fetch the filtered billing count for the lab
                    $totalBillCount = BillingFlow::where('lab_id', $lab->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count();

                    return response()->json([
                        'status' => 200,
                        'total' => $totalBillCount,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Fatal error',
                        'error' => $e->getMessage(),
                    ]);
                }
            }


            // Get total revenue for a lab | mix dashboard
            public function getLabTotalRevenue(Request $request) {
                try {
                    // Get the authenticated user
                    $userData = $request->user();

                    // Fetch the lab ID associated with the user
                    $lab = LabModel::where('user_id', $userData->id)->first();

                    if (!$lab) {
                        return response()->json([
                            'status' => 404,
                            'message' => 'Lab not found for the user',
                        ]);
                    }

                    // Fetch the total revenue for the lab
                    $totalRevenue = BillingFlow::where('lab_id', $lab->id)->sum('final_amount');

                    return response()->json([
                        'status' => 200,
                        'total' => $totalRevenue,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch total revenue',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }


            // Get total lab revenue (with date range) || for lab dashboard
            public function getLabFilteredRevenue(Request $request) {
                try {
                    // Get the authenticated user
                    $userData = $request->user();

                    // Fetch the lab ID associated with the user
                    $lab = LabModel::where('user_id', $userData->id)->first();

                    if (!$lab) {
                        return response()->json([
                            'status' => 404,
                            'message' => 'Lab not found for the user',
                        ]);
                    }

                    // Get the date range from the request
                    $startDate = $request->query('start_date');
                    $endDate = $request->query('end_date');

                    // Fetch the filtered total revenue for the lab
                    $query = BillingFlow::where('lab_id', $lab->id);

                    if ($startDate && $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    }

                    $totalRevenue = $query->sum('final_amount');

                    return response()->json([
                        'status' => 200,
                        'total' => $totalRevenue,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch filtered total revenue',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }

            // Get total lab revenue against each employee (with date range) || for lab dashboard
            public function employeeLabRevenue(Request $request)
            {
                try {
                    $userData = $request->user();
                    $lab = LabModel::where('user_id', $userData->id)->first();

                    if (!$lab) {
                        return response()->json([
                            'status' => 404,
                            'message' => 'Lab not found for the user',
                        ]);
                    }

                    $startDate = $request->query('start_date');
                    $endDate = $request->query('end_date');
                    $employeeId = $request->query('employee_id');

                    $totalRevenue = BillingFlow::where('lab_id', $lab->id)
                    ->when($startDate && $endDate, fn($q) => 
                        $q->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
                    )
                    ->when($employeeId, fn($q) => $q->where('selected_employee_id', $employeeId))
                    ->sum('final_amount');

                    return response()->json([
                        'status' => 200,
                        'total' => $totalRevenue,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch filtered total revenue',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }

    /////////// LAB REPORTS SECTION ENDS HERE ////////////
    /////////// LAB REPORTS SECTION ENDS HERE ////////////
    /////////// LAB REPORTS SECTION ENDS HERE ////////////












    /////////// DOCTOR REPORTS SECTION STARTS HERE ///////
    /////////// DOCTOR REPORTS SECTION STARTS HERE ///////
    /////////// DOCTOR REPORTS SECTION STARTS HERE ///////

            // Get total doctors and workers
            public function getTotalDoctorsAndWorkers()
            {
                try {
                    $total = DoctorAndWorker::count();
                    return response()->json([
                        'status' => 200,
                        'total' => $total,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch total doctors and workers',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }

            // Get total labs and hospitals
            public function getTotalLabsAndHospitals()
            {
                try {
                    $total = LabModel::count();
                    return response()->json([
                        'status' => 200,
                        'total' => $total,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch total labs and hospitals',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }

            // Get total patients
            public function getTotalPatients()
            {
                try {
                    $total = PatientData::count();
                    return response()->json([
                        'status' => 200,
                        'total' => $total,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch total patients',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }


            // Get filtered total patients 
            public function getTotalFilteredPatients(Request $request)
            {
                try {
                    $startDate = $request->query('start_date');
                    $endDate = $request->query('end_date');

                    $query = PatientData::query();

                    if ($startDate) {
                        $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
                    }

                    if ($endDate) {
                        $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
                    }

                    $total = $query->count();

                    return response()->json([
                        'status' => 200,
                        'total' => $total,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch filtered total patients',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }


            // Get total assigned patients (without filter)
            public function getTotalAssignedPatients()
            {
                try {
                    $total = PatientAssignFlow::where('billing_status', 'pending')->count();
                    
                    return response()->json([
                        'status' => 200,
                        'total' => $total,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch total assigned patients',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }


            // Get filtered assigned patients (with date range)
            public function getFilteredAssignedPatients(Request $request)
            {
                try {
                    $startDate = $request->query('start_date');
                    $endDate = $request->query('end_date');

                    $query = PatientAssignFlow::query();

                    if ($startDate) {
                        $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
                    }

                    if ($endDate) {
                        $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
                    }

                    $total = $query->count();

                    return response()->json([
                        'status' => 200,
                        'total' => $total,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch filtered assigned patients',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }


            // Get total billed patients (without filter)
            public function getTotalBilledPatients()
            {
                try {
                    $total = BillingFlow::count();
                    return response()->json([
                        'status' => 200,
                        'total' => $total,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch total billed patients',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }

            // Get filtered billed patients (with date range)
            public function getFilteredBilledPatients(Request $request)
            {
                try {
                    $startDate = $request->query('start_date');
                    $endDate = $request->query('end_date');

                    $query = BillingFlow::query();

                    if ($startDate) {
                        $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
                    }

                    if ($endDate) {
                        $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
                    }

                    $total = $query->count();

                    return response()->json([
                        'status' => 200,
                        'total' => $total,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch filtered billed patients',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }


            // Get total revenue (without filter)
            public function getTotalRevenue()
            {
                try {
                    $total = BillingFlow::sum('final_amount');
                    return response()->json([
                        'status' => 200,
                        'total' => $total,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch total revenue',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }

            // Get filtered revenue (with date range)
            public function getFilteredRevenue(Request $request) {
                try {
                    $startDate = $request->query('start_date');
                    $endDate = $request->query('end_date');

                    $query = BillingFlow::query();

                    if ($startDate) {
                        $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
                    }

                    if ($endDate) {
                        $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
                    }

                    $total = $query->sum('final_amount');

                    return response()->json([
                        'status' => 200,
                        'total' => $total,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch filtered revenue',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }


            // Get lab revenue with data range specific labs 

            public function AdminLabRevenueWithData(Request $request)
            {
                try {
                    // Validate request parameters
                    $request->validate([
                        'lab_id' => 'required|integer',
                        'start_date' => 'required|date',
                        'end_date' => 'required|date',
                    ]);

                    $labId = $request->input('lab_id');
                    $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                    $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

                    // Fetch lab details
                    $lab = LabModel::find($labId);

                    if (!$lab) {
                        return response()->json([
                            'status' => 404,
                            'message' => 'Lab not found',
                        ]);
                    }

                    // Fetch revenue for the selected lab
                    $totalRevenue = BillingFlow::where('lab_id', $labId)
                        ->where('created_at', '>=', $startDate)
                        ->where('created_at', '<=', $endDate)
                        ->sum('final_amount');

                    return response()->json([
                        'status' => 200,
                        'data' => [
                            'lab_id' => $lab->id,
                            'lab_name' => $lab->name,
                            'total_revenue' => $totalRevenue,
                        ],
                        'message' => 'Revenue for selected lab fetched successfully',
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch revenue',
                        'error' => $e->getMessage(),
                    ]);
                }
            }


            public function AdminLabRevenueByEmployee(Request $request)
            {
                try {
                    // Validate request parameters
                    $request->validate([
                        'lab_id' => 'required|integer',
                        'employee_id' => 'required|integer',
                        'start_date' => 'required|date',
                        'end_date' => 'required|date',
                    ]);
            
                    $labId = $request->input('lab_id');
                    $employeeId = $request->input('employee_id');
                    $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                    $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            
                    // Fetch lab details
                    $lab = LabModel::find($labId);
            
                    if (!$lab) {
                        return response()->json([
                            'status' => 404,
                            'message' => 'Lab not found',
                        ]);
                    }
            
                    // Fetch employee details
                    $employee = Employee::where('id', $employeeId)->first();
            
                    if (!$employee) {
                        return response()->json([
                            'status' => 404,
                            'message' => 'Employee not found',
                        ]);
                    }
            
                    // Fetch revenue for the selected lab and employee
                    $totalRevenue = BillingFlow::where('lab_id', $labId)
                        ->where('selected_employee_id', $employeeId)
                        ->where('created_at', '>=', $startDate)
                        ->where('created_at', '<=', $endDate)
                        ->sum('final_amount');
            
                    return response()->json([
                        'status' => 200,
                        'data' => [
                            'lab_id' => $lab->id,
                            'lab_name' => $lab->name,
                            'employee_id' => $employee->id,
                            'employee_name' => $employee->name,
                            'total_revenue' => $totalRevenue,
                        ],
                        'message' => 'Revenue for selected lab and employee fetched successfully',
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch revenue',
                        'error' => $e->getMessage(),
                    ]);
                }

                // employee model has just id this is where employee id are stored 
                // 
            }
            
            

            
    /////////// DOCTOR REPORTS SECTION ENDS HERE /////////
    /////////// DOCTOR REPORTS SECTION ENDS HERE /////////
    /////////// DOCTOR REPORTS SECTION ENDS HERE /////////
      




    /////// swastha sewek reports starts here
    /////// swastha sewek reports starts here
    /////// swastha sewek reports starts here


            public function getWorkerTotalAssignedPatients(Request $request) 
            {
                try {
                    $userData = $request->user();
            
                    // Fetch patient IDs directly from patient_assign_flows
                    $assignedPatientIds = PatientAssignFlow::pluck('patient_id');
            
                    // Count patients that are assigned and linked to the user
                    $totalAssignedPatients = PatientData::whereIn('id', $assignedPatientIds)
                        ->where('associated_user_id', $userData->id) // Ensure filtering by associated user
                        ->count();
            
                    return response()->json([
                        'status' => 200,
                        'total' => $totalAssignedPatients,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch total assigned patients',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }
    
            
        
            // Get total patients billed by the user
            public function getWorkerTotalBilledPatients(Request $request)
            {
                try {
                    $userData = $request->user();

                    // Fetch patient IDs directly
                    $patientIds = PatientData::Where('associated_user_id', $userData->id)
                        ->pluck('id');

                    // Count billed patients using the fetched IDs
                    $totalBilledPatients = BillingFlow::whereIn('patient_id', $patientIds)
                    ->where('associated_user_id', $userData->id)->count();

                    return response()->json([
                        'status' => 200,
                        'total' => $totalBilledPatients,
                    ]);
                } catch (Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch total billed patients',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }



            // Get total revenue generated by the user
            public function getWorkerTotalRevenue(Request $request)
            {
                try {
                    $userData = $request->user();

                  
                    // Fetch patient IDs directly
                    $patientIds = PatientData::where('associated_user_id', $userData->id)
                        ->pluck('id');

                    // Fetch total revenue for these patients
                    $billingQuery = BillingFlow::whereIn('patient_id', $patientIds)
                        ->where('associated_user_id', $userData->id);

                    // Apply date filter if provided
                    if ($request->has('start_date')) {
                        $billingQuery->whereDate('created_at', '>=', $request->start_date);
                    }
                    if ($request->has('end_date')) {
                        $billingQuery->whereDate('created_at', '<=', $request->end_date);
                    }

                    $totalRevenue = $billingQuery->sum('final_amount');

                    return response()->json([
                        'status' => 200,
                        'total' => $totalRevenue,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to fetch total revenue',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }



    /////// ends here
    /////// ends here
    /////// ends here

}