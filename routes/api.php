<?php

use App\Http\Controllers\AccountRegister\AccountRegister;
use App\Http\Controllers\Admin\DoctorController\DoctorController;
use App\Http\Controllers\Admin\LabController\LabController;
use App\Http\Controllers\Admin\LabEmployee\EmployeeController;
use App\Http\Controllers\Admin\LabTestController\TestCategoryController;
use App\Http\Controllers\Admin\LabTestController\TestController;
use App\Http\Controllers\Admin\PatientController\PatientLocationController;
use App\Http\Controllers\AuthController\AdminLogin;
use App\Http\Controllers\AuthController\AuthController;
use App\Http\Controllers\Lab\BillingFlow\BillingFlowController;
use App\Http\Controllers\Reports\ReportsController;
use App\Http\Controllers\Reports\UserDashboardController;
use App\Http\Controllers\User\PatientAssignFlow\PatientAssignFlow;
use App\Http\Controllers\User\PatientController\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// OAuth callback route for future use, not currently used in the project, however setup is done.
Route::get('/auth/{provider}/callback', [AuthController::class, 'googleCallback']);
// Ends here


// User Authentication Routes 

        // Admin Authentication controller starts here 
        Route::post('/admin-register', [AccountRegister::class, 'adminRegister']);
        Route::post('/admin-login', [AdminLogin::class, 'adminLogin']);
       
        // using adminLogin controller for user, lab and hospital || 
        Route::post('/user-login', [AdminLogin::class, 'userLogin']); // doctor and worker login in admin controller 
        Route::post('/lab-login', [AdminLogin::class, 'labLogin']);
        Route::post('/hospital-login', [AdminLogin::class, 'hospitalLogin']);
        // Ends here
        
// Auth Routes Ends here


        Route::middleware(['auth:sanctum'])->group(function () {
           
            Route::prefix('admin/doctors')->group(function () {
                // testing get and post
                Route::get('/test', [DoctorController::class, 'test']);
                Route::post('/newTest', [DoctorController::class, 'newTest']);

                // doctor routes begins here
                Route::post('/add-doctor', [DoctorController::class, 'addDoctor']);
                Route::post('/view-doctor', [DoctorController::class, 'viewDoctor']);

                Route::post('/view-pending-accounts', [DoctorController::class, 'viewPendingAccounts']);
                Route::get('/search-pending-accounts', [DoctorController::class, 'searchPendingAccounts']);
                Route::post('/accept-pending-accounts', [DoctorController::class, 'acceptPendingAccounts']);
                Route::delete('/delete-pending-account/{id}', [DoctorController::class, 'deletePendingAccount']);

                Route::post('/fetch-doctor/{id}', [DoctorController::class, 'fetchSingleDoctor']);
                Route::get('/fetch-doctorCred/{Id}', [DoctorController::class, 'fetchDoctorsCred']);
                Route::post('/change-doctorPsw/{Id}', [DoctorController::class, 'changeDocPsw']);
                Route::post('/update-doctor/{Id}', [DoctorController::class, 'updateDoctorData']);
                Route::get('/disable-doctor/{Id}', [DoctorController::class, 'disableDoctorData']);

                // Route for autoSearch doc and workers account 
                Route::get('/search-users', [DoctorController::class, 'autoSearchUser']);
            });

            
            Route::prefix('admin/lab')->group(function () {

                // Route for direct lab registration 
                Route::post('/add-lab', [LabController::class, 'addLab']);
                Route::get('/fetch-lab-account-data', [LabController::class, 'fetchLabData']);
                Route::get('/fetch-lab-single-account-data/{id}', [LabController::class, 'fetchSingleLabData']);
                Route::post('/update-lab-data/{id}', [LabController::class, 'updateLabUser']);
                Route::get('/disable-lab/{Id}', [LabController::class, 'disableLabData']);
                Route::post('/change-lab-psw/{id}', [LabController::class, 'changeLabPsw']);
                
                   
                // Route for autoSearch doc and workers account 
                Route::get('/lab-search', [LabController::class, 'labSearch']);
                // ends here       
            
            });

             
            Route::prefix('admin/employee')->group(function () {
                // Route for admin adding employee against labs
                Route::post('/add-employee/{id}', [EmployeeController::class, 'addEmployeeAgainstLab']);
                Route::get('/fetch-lab-employee', [EmployeeController::class, 'fetchEmployee']);
                Route::post('/fetch-specific-lab-employees', [EmployeeController::class, 'fetchSpecificLabEmployees']);
                Route::get('/disable-employee/{id}', [EmployeeController::class, 'disableEmployee']);
            });


            Route::prefix('admin/lab-test')->group(function () {

                 // Route for admin adding diagnostic test master 
                Route::post('/add-test-category', [TestCategoryController::class, 'addTestCategory']);
                Route::post('/add-lab-test', [TestController::class, 'addTest']);
                Route::get('/fetch-test-category', [TestCategoryController::class, 'fetchTestCategory']);
                Route::get('/fetch-test/{id}', [TestController::class, 'fetchTestWithId']);
                Route::get('/edit-test-category/{id}', [TestCategoryController::class, 'editTestCategory']);
                Route::post('/update-test-category/{id}', [TestCategoryController::class, 'updateTestCategory']);
                Route::get('/edit-lab-test/{id}', [TestController::class, 'editLabTest']);
                Route::post('/update-lab-test/{id}', [TestController::class, 'updateLabTest']);

                /////// Search for lab test api
                Route::get('/search-lab-test', [TestController::class, 'searchLabTests']);

                /////// Disable test category 
                Route::get('/disable-test-category/{id}', [TestCategoryController::class, 'disableTestCategory']);
                Route::get('/disable-test/{id}', [TestController::class, 'disableTest']);
            });

        });


        Route::middleware(['auth:sanctum'])->group(function () {
           
            Route::prefix('user/patient-location')->group(function () {
                // testing get and post
                Route::get('/test', [DoctorController::class, 'test']);
                Route::post('/newTest', [DoctorController::class, 'newTest']);

                /////// Route for adding patient location
                Route::post('/add-patient-location', [PatientLocationController::class, 'addPatientLocation']);
                Route::post('/fetch-patient-location', [PatientLocationController::class, 'viewPatientLocation']);
 
                // updating patient location data
                Route::post('/update-patient-location', [PatientLocationController::class, 'updatePatientLocation']);
                
                // disabling patient location data
                Route::get('/disable-patient-location/{id}', [PatientLocationController::class, 'disablePatientLocation']);

                // fetching patient all data
                Route::post('/fetch-patient-all-location', [PatientLocationController::class, 'fetchPatientAllLocation']);
                /////// Ends here    
            });

            Route::prefix('user/patient-crud')->group(function () {

                Route::post('add-patient', [PatientController::class, 'addPatient']);
                Route::get('view-patient', [PatientController::class, 'viewPatient']);
                Route::get('edit-patient/{id}', [PatientController::class, 'editPatient']);
                Route::post('update-patient/{id}', [PatientController::class, 'updatePatient']);
                Route::get('patient-full-info/{id}', [PatientController::class, 'patientFullInfo']);

                Route::get('search-patient', [PatientController::class, 'searchPatients']);
                Route::get('view-patient-card/{id}', [PatientController::class, 'viewPatientCard']);

            });

            Route::prefix('user/patient-assign-flow')->group( function() {

                Route::get('searching/test', [PatientAssignFlow::class, 'searchingTest']);
                Route::post('assigning/test', [PatientAssignFlow::class, 'assigningTest']);
                Route::get('view-assigned-patients', [PatientAssignFlow::class, 'viewAssignedPatients']); 
                Route::get('search-assigned-patient', [PatientAssignFlow::class, 'searchAssignedPatient']); 
                Route::get('check-visit', [PatientAssignFlow::class, 'checkVisit']);

            });

        });


        Route::middleware(['auth:sanctum'])->group(function () {
           
            Route::prefix('lab/flow')->group( function() {

                Route::get('/view-patient/{id}', [BillingFlowController::class, 'viewPatientById']);
                Route::post('/submit-billing', [BillingFlowController::class, 'submitBilling']);
                
                Route::get('/view-paid-patients', [BillingFlowController::class, 'viewPaidPatients']);
                Route::get('/search-paid-patients', [BillingFlowController::class, 'searchPaidPatients']);

                Route::get('/view-patient-bill/{id}', [BillingFlowController::class, 'ViewPatientBillPdf']);

                /// Routes for pending patient work 

                Route::get('/view-pending-patients', [BillingFlowController::class, 'viewPendingPatients']);
                Route::get('/search-pending-patients', [BillingFlowController::class, 'searchPendingPatients']);

            });

        });


        // report || dashboard work starts here

        Route::middleware(['auth:sanctum'])->group(function () {
           
            Route::prefix('report/lab')->group( function() {

                // total patient that are billed in the lab
                Route::get('/patient-billing', [ReportsController::class, 'getBillingCount']);
                Route::get('/patient-billing/filter', [ReportsController::class, 'getFilteredBillingCount']);
               
                // Total revenue generated
                Route::get('/total-revenue', [ReportsController::class, 'getTotalRevenue']);
                Route::get('/total-revenue/filter', [ReportsController::class, 'getFilteredRevenue']);
                
                // Total revenue generated by lab employee filtered data 
                Route::get('/employee-revenue/filter', [ReportsController::class, 'employeeLabRevenue']);

            });

            // Admin Dashboard Routes
            Route::prefix('admin')->group(function () {
                // Total Doctors and Workers
                Route::get('/total-doctors-workers', [ReportsController::class, 'getTotalDoctorsAndWorkers']);

                // Total Labs and Hospitals
                Route::get('/total-labs-hospitals', [ReportsController::class, 'getTotalLabsAndHospitals']);

                // Total Patients
                Route::get('/total-patients', [ReportsController::class, 'getTotalPatients']);
                Route::get('/total-filtered-patients', [ReportsController::class, 'getTotalFilteredPatients']);

                // Total Assigned Patients (with and without filter)
                Route::get('/total-assigned-patients', [ReportsController::class, 'getTotalAssignedPatients']);
                Route::get('/total-assigned-patients/filter', [ReportsController::class, 'getFilteredAssignedPatients']);

                // Total Billed Patients (with and without filter)
                Route::get('/total-billed-patients', [ReportsController::class, 'getTotalBilledPatients']);
                Route::get('/total-billed-patients/filter', [ReportsController::class, 'getFilteredBilledPatients']);

                // Total Revenue (with and without filter)
                Route::get('/total-revenue', [ReportsController::class, 'getTotalRevenue']);
                Route::get('/total-revenue/filter', [ReportsController::class, 'getFilteredRevenue']);

                // Total revenue against each lab with data range 
                // No need of another route to see total lab sum revenue already done 
                Route::get('/total-revenue-by-lab/filter', [ReportsController::class, 'AdminLabRevenueWithData']);


                Route::get('/total-revenue-by-lab-employee/filter', [ReportsController::class, 'AdminLabRevenueByEmployee']);
               

            });  

            // User Dashboard Routes
            Route::prefix('workers')->group(function () {

                 // Total patients assigned to the user
                    Route::get('/total-assigned-patients', [ReportsController::class, 'getWorkerTotalAssignedPatients']);

                    // Total patients billed by the user
                    Route::get('/total-billed-patients', [ReportsController::class, 'getWorkerTotalBilledPatients']);

                    // Total revenue generated by the user
                    Route::get('/total-revenue', [ReportsController::class, 'getWorkerTotalRevenue']);
    
            });  

        });


