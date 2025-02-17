<?php

namespace App\Http\Controllers;

use App\Models\DoctorAndWorker;
use App\Models\Employee;
use App\Models\LabModel;
use App\Models\PatientData;
use App\Models\Test;
use App\Models\TestCategory;
use Exception;
use Illuminate\Http\Request;

class DisableEnableController extends Controller
{
   

    // handling patient enable and disable option || starts here 

    public function disablePatients($id) {

        try {

            $patient = PatientData::where('id', $id)->first();

            if (!$patient) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Patient not found'
                ], 404);
            }

            $patient->disable_status = 1; 
            $patient->save();

            return response()->json([
                'status' => 200,
                'message' => 'Patient disabled successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function enablePatients($id)
    {
        try {
            $patient = PatientData::where('id', $id)->first();
    
            if (!$patient) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Patient not found'
                ], 404);
            }
    
            $patient->disable_status = 0;
            $patient->save();
    
            return response()->json([
                'status' => 200,
                'message' => 'Patient enabled successfully'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function fetchDisablePatients(Request $request)
    {
        try {
            // Get pagination parameters from the request
            $page = $request->input('page', 1); // Default to page 1 if not provided
            $recordsPerPage = $request->input('recordsPerPage', 10); // Default to 10 records per page if not provided

            // Fetch paginated patients with disable_status = 1
            $patients = PatientData::where('disable_status', 1)
                ->paginate($recordsPerPage, ['*'], 'page', $page);

            return response()->json([
                'status' => 200,
                'message' => 'Items fetched successfully',
                'data' => $patients->items(), // Paginated data
                'total' => $patients->total(), // Total number of records
                'currentPage' => $patients->currentPage(), // Current page
                'recordsPerPage' => $patients->perPage(), // Records per page
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching patients',
                'error' => $e->getMessage(),
            ]);
        }
    }


    public function searchDisablePatients(Request $request)
    {
        try {
            // Get the search query from the request
            $query = $request->input('query', '');

            // Validate the query (optional)
            if (empty($query)) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Search query is required',
                ]);
            }

            // Search for patients with disable_status = 1 and matching name or phone
            $patients = PatientData::where('disable_status', 1)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('phone', 'like', '%' . $query . '%');
                })
                ->get();

            return response()->json([
                'status' => 200,
                'message' => 'Search results fetched successfully',
                'data' => $patients,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching search results',
                'error' => $e->getMessage(),
            ]);
        }
    }

    // handling patient enable and disable option || ends here




    // handle user disable and enable || starts here

    public function disableUser($id) {
        try {
            $user = DoctorAndWorker::where('id', $id)->first();
    
            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found'
                ], 404);
            }
    
            $user->disable_status = 1; // Disable the user
            $user->save();
    
            return response()->json([
                'status' => 200,
                'message' => 'User disabled successfully'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function enableUser($id) {
        try {
            $user = DoctorAndWorker::where('id', $id)->first();
    
            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found'
                ], 404);
            }
    
            $user->disable_status = 0; // Enable the user
            $user->save();
    
            return response()->json([
                'status' => 200,
                'message' => 'User enabled successfully'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function fetchDisabledUsers(Request $request) {
        try {
            // Get pagination parameters from the request
            $page = $request->input('page', 1); // Default to page 1 if not provided
            $recordsPerPage = $request->input('recordsPerPage', 10); // Default to 10 records per page if not provided
    
            // Fetch paginated users with disable_status = 1
            $users = DoctorAndWorker::where('disable_status', 1)
                ->paginate($recordsPerPage, ['*'], 'page', $page);
    
            return response()->json([
                'status' => 200,
                'message' => 'Items fetched successfully',
                'data' => $users->items(), // Paginated data
                'total' => $users->total(), // Total number of records
                'currentPage' => $users->currentPage(), // Current page
                'recordsPerPage' => $users->perPage(), // Records per page
            ]);
    
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching users',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function searchDisabledUsers(Request $request) {
        try {
            // Get the search query from the request
            $query = $request->input('query', '');
    
            // Validate the query (optional)
            if (empty($query)) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Search query is required',
                ]);
            }
    
            // Search for users with disable_status = 1 and matching name or email
            $users = DoctorAndWorker::where('disable_status', 1)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                      ->orWhere('email', 'like', '%' . $query . '%');
                })
                ->get();
    
            return response()->json([
                'status' => 200,
                'message' => 'Search results fetched successfully',
                'data' => $users,
            ]);
    
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching search results',
                'error' => $e->getMessage(),
            ]);
        }
    }

     // handle user disable and enable || ends here



     // handle lab disable and enable || starts here
     
    public function disableLab($id) {
        try {
            $lab = LabModel::where('id', $id)->first();
    
            if (!$lab) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Lab not found'
                ], 404);
            }
    
            $lab->disable_status = 1; // Disable the lab
            $lab->save();
    
            return response()->json([
                'status' => 200,
                'message' => 'Lab disabled successfully'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function enableLab($id) {
        try {
            $lab = LabModel::where('id', $id)->first();
    
            if (!$lab) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Lab not found'
                ], 404);
            }
    
            $lab->disable_status = 0; // Enable the lab
            $lab->save();
    
            return response()->json([
                'status' => 200,
                'message' => 'Lab enabled successfully'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function fetchDisabledLabs(Request $request) {
        try {
            // Get pagination parameters from the request
            $page = $request->input('page', 1); // Default to page 1 if not provided
            $recordsPerPage = $request->input('recordsPerPage', 10); // Default to 10 records per page if not provided
    
            // Fetch paginated labs with disable_status = 1
            $labs = LabModel::where('disable_status', 1)
                ->paginate($recordsPerPage, ['*'], 'page', $page);
    
            return response()->json([
                'status' => 200,
                'message' => 'Labs fetched successfully',
                'data' => $labs->items(), // Paginated data
                'total' => $labs->total(), // Total number of records
                'currentPage' => $labs->currentPage(), // Current page
                'recordsPerPage' => $labs->perPage(), // Records per page
            ]);
    
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching labs',
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    public function searchDisabledLabs(Request $request) {
        try {
            // Get the search query from the request
            $query = $request->input('query', '');
    
            // Validate the query (optional)
            if (empty($query)) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Search query is required',
                ]);
            }
    
            // Search for labs with disable_status = 1 and matching name or email
            $labs = LabModel::where('disable_status', 1)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                      ->orWhere('email', 'like', '%' . $query . '%');
                })
                ->get();
    
            return response()->json([
                'status' => 200,
                'message' => 'Search results fetched successfully',
                'data' => $labs,
            ]);
    
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching search results',
                'error' => $e->getMessage(),
            ]);
        }
    }

     // handle lab disable and enable || ends here 


     // handle employee disable and enable || starts here

    public function disableEmployee($id) {
        try {
            $employee = Employee::where('id', $id)->first();
    
            if (!$employee) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Employee not found'
                ], 404);
            }
    
            $employee->disable_status = 1; // Disable the employee
            $employee->save();
    
            return response()->json([
                'status' => 200,
                'message' => 'Employee disabled successfully'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function enableEmployee($id) {
        try {
            $employee = Employee::where('id', $id)->first();
    
            if (!$employee) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Employee not found'
                ], 404);
            }
    
            $employee->disable_status = 0; // Enable the employee
            $employee->save();
    
            return response()->json([
                'status' => 200,
                'message' => 'Employee enabled successfully'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function fetchDisabledEmployees(Request $request) {
        try {
            // Get pagination parameters from the request
            $page = $request->input('page', 1); // Default to page 1 if not provided
            $recordsPerPage = $request->input('recordsPerPage', 10); // Default to 10 records per page if not provided
    
            // Fetch paginated employees with disable_status = 1
            $employees = Employee::where('disable_status', 1)
                ->paginate($recordsPerPage, ['*'], 'page', $page);
    
            return response()->json([
                'status' => 200,
                'message' => 'Employees fetched successfully',
                'data' => $employees->items(), // Paginated data
                'total' => $employees->total(), // Total number of records
                'currentPage' => $employees->currentPage(), // Current page
                'recordsPerPage' => $employees->perPage(), // Records per page
            ]);
    
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching employees',
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    public function searchDisabledEmployees(Request $request) {
        try {
            // Get the search query from the request
            $query = $request->input('query', '');
    
            // Validate the query (optional)
            if (empty($query)) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Search query is required',
                ]);
            }
    
            // Search for employees with disable_status = 1 and matching name or email
            $employees = Employee::where('disable_status', 1)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                      ->orWhere('email', 'like', '%' . $query . '%');
                })
                ->get();
    
            return response()->json([
                'status' => 200,
                'message' => 'Search results fetched successfully',
                'data' => $employees,
            ]);
    
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching search results',
                'error' => $e->getMessage(),
            ]);
        }
    }

     // handle employee disable and enable|| ends here




     // handle employee test category || starts here

        public function disableTestCategory($id) {
            try {
                $testCategory = TestCategory::where('id', $id)->first();
        
                if (!$testCategory) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Test category not found'
                    ], 404);
                }
        
                $testCategory->disable_status = 1; // Disable the test category
                $testCategory->save();
        
                return response()->json([
                    'status' => 200,
                    'message' => 'Test category disabled successfully'
                ], 200);
        
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Fatal error',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
        
        public function enableTestCategory($id) {
            try {
                $testCategory = TestCategory::where('id', $id)->first();
        
                if (!$testCategory) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Test category not found'
                    ], 404);
                }
        
                $testCategory->disable_status = 0; // Enable the test category
                $testCategory->save();
        
                return response()->json([
                    'status' => 200,
                    'message' => 'Test category enabled successfully'
                ], 200);
        
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Fatal error',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
        
        public function fetchDisabledTestCategories(Request $request) {
            try {
                // Get pagination parameters from the request
                $page = $request->input('page', 1); // Default to page 1 if not provided
                $recordsPerPage = $request->input('recordsPerPage', 10); // Default to 10 records per page if not provided
        
                // Fetch paginated test categories with disable_status = 1
                $testCategories = TestCategory::where('disable_status', 1)
                    ->paginate($recordsPerPage, ['*'], 'page', $page);
        
                return response()->json([
                    'status' => 200,
                    'message' => 'Test categories fetched successfully',
                    'data' => $testCategories->items(), // Paginated data
                    'total' => $testCategories->total(), // Total number of records
                    'currentPage' => $testCategories->currentPage(), // Current page
                    'recordsPerPage' => $testCategories->perPage(), // Records per page
                ]);
        
            } catch (Exception $e) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Error fetching test categories',
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        public function searchDisabledTestCategories(Request $request) {
            try {
                // Get the search query from the request
                $query = $request->input('query', '');
        
                // Validate the query (optional)
                if (empty($query)) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Search query is required',
                    ]);
                }
        
                // Search for test categories with disable_status = 1 and matching name or description
                $testCategories = TestCategory::where('disable_status', 1)
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%');
                    })
                    ->get();
        
                return response()->json([
                    'status' => 200,
                    'message' => 'Search results fetched successfully',
                    'data' => $testCategories,
                ]);
        
            } catch (Exception $e) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Error fetching search results',
                    'error' => $e->getMessage(),
                ]);
            }
        }

     // handle employee test category || ends here




     // handle tests disable and enable || starts here

        public function disableTest($id) {
            try {
                $test = Test::where('id', $id)->first();
        
                if (!$test) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Test not found'
                    ], 404);
                }
        
                $test->disable_status = 1; // Disable the test
                $test->save();
        
                return response()->json([
                    'status' => 200,
                    'message' => 'Test disabled successfully'
                ], 200);
        
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Fatal error',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
        
        public function enableTest($id) {
            try {
                $test = Test::where('id', $id)->first();
        
                if (!$test) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Test not found'
                    ], 404);
                }
        
                $test->disable_status = 0; // Enable the test
                $test->save();
        
                return response()->json([
                    'status' => 200,
                    'message' => 'Test enabled successfully'
                ], 200);
        
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Fatal error',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
        
        public function fetchDisabledTests(Request $request) {
            try {
                // Get pagination parameters from the request
                $page = $request->input('page', 1); // Default to page 1 if not provided
                $recordsPerPage = $request->input('recordsPerPage', 10); // Default to 10 records per page if not provided
        
                // Fetch paginated tests with disable_status = 1
                $tests = Test::where('disable_status', 1)
                    ->paginate($recordsPerPage, ['*'], 'page', $page);
        
                return response()->json([
                    'status' => 200,
                    'message' => 'Tests fetched successfully',
                    'data' => $tests->items(), // Paginated data
                    'total' => $tests->total(), // Total number of records
                    'currentPage' => $tests->currentPage(), // Current page
                    'recordsPerPage' => $tests->perPage(), // Records per page
                ]);
        
            } catch (Exception $e) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Error fetching tests',
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        public function searchDisabledTests(Request $request) {
            try {
                // Get the search query from the request
                $query = $request->input('query', '');
        
                // Validate the query (optional)
                if (empty($query)) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Search query is required',
                    ]);
                }
        
                // Search for tests with disable_status = 1 and matching name or description
                $tests = Test::where('disable_status', 1)
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%');
                    })
                    ->get();
        
                return response()->json([
                    'status' => 200,
                    'message' => 'Search results fetched successfully',
                    'data' => $tests,
                ]);
        
            } catch (Exception $e) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Error fetching search results',
                    'error' => $e->getMessage(),
                ]);
            }
        }

     // ends here

}
