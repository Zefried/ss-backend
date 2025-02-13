<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BillingFlow;
use App\Models\DoctorAndWorker;
use App\Models\LabModel;
use App\Models\PatientData;
use App\Models\PatientAssignFlow;

class ReportsController extends Controller
{
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

    // Get total revenue for a lab
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

    // Get filtered total revenue for a lab (with date range)
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

    /////////// LAB REPORTS SECTION ENDS HERE ////////////












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

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
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

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
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
    public function getFilteredRevenue(Request $request)
    {
        try {
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');

            $query = BillingFlow::query();

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
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

    /////////// DOCTOR REPORTS SECTION ENDS HERE /////////
}