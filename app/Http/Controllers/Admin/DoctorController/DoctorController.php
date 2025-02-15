<?php

namespace App\Http\Controllers\Admin\DoctorController;

use App\Http\Controllers\Controller;
use App\Models\DoctorAndWorker;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function test(){
        return response()->json(['message' => 'Hello World']);
    }



    // web route to open the blade

    public function testDoctor(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'designation' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'age' => 'required|numeric',
                'sex' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255|unique:users,email',
                'consent_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'village' => 'nullable|string|max:255',
                'district' => 'required|nullable|string|max:255',
                'buildingNo' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'account_request' => 'required|',
            ]);
    
            // Check if the email already exists in the DoctorAndWorker model
            if (User::where('email', $validated['email'])->exists()) {
                return redirect()->back()
                    ->withErrors(['email' => 'The email already exists in the DoctorAndWorker model.'])
                    ->withInput();
            }
    
            // Check if the phone already exists in the DoctorAndWorker model
            if (DoctorAndWorker::where('phone', $validated['phone'])->exists()) {
                return redirect()->back()
                    ->withErrors(['phone' => 'The phone number already exists in the DoctorAndWorker model.'])
                    ->withInput();
            }
    
            // Handle file upload
            if ($request->hasFile('consent_file')) {
                $file = $request->file('consent_file');
                $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $filePath = "consent_files/$fileName";
    
                // Store new file
                $file->storeAs('consent_files', $fileName, 'public');
    
                // Add the file path to the validated data
                $validated['consent_file'] = $filePath;
            }
    
            // Create a new record in the DoctorAndWorker model
            DoctorAndWorker::create($validated);
    
            return redirect()->route('form.submit')->with('success', 'Form submitted successfully!');
    
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])
                ->withInput();
        }
    }
    

    public function showForm($step)
    {
        return view('doctor_worker', ['step' => $step]);
    }

    public function indexFile()
    {
        return view('index');
    }
    
    public function viewPendingAccounts(Request $request) {

        // Set default values for pagination
        $page = $request->input('page', 1);
        $recordsPerPage = $request->input('recordsPerPage', 10);

        // Query the DoctorAndWorker model for pending accounts
        $query = DoctorAndWorker::where('account_request', true);

        // Get the total count of pending accounts
        $total = $query->count();

        // Paginate the results
        $listData = $query->paginate($recordsPerPage, ['*'], 'page', $page);

        // Calculate the last page number
        $lastPage = $listData->lastPage();

        // Prepare the response data
        $response = [
            'status' => 200,
            'message' => 'Pending accounts fetched successfully.',
            'listData' => $listData->items(),
            'total' => $total,
            'last_page' => $lastPage,
            'current_page' => $listData->currentPage(),
        ];

        // Return the JSON response
        return response()->json($response);
    }

    public function searchPendingAccounts(Request $request)
    {
        // Validate the request for the search query
        $request->validate([
            'query' => 'required|string|min:1', // Ensure a search query is provided
        ]);

        // Get the search query from the request
        $query = $request->input('query');

        // Query the DoctorAndWorker model for pending accounts
        $results = DoctorAndWorker::where('account_request', true)
            ->where(function ($q) use ($query) {
                // Search by name, email, phone, or any other relevant fields
                $q->where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%")
                ->orWhere('workDistrict', 'like', "%{$query}%");
            })
            ->get();

        // Check if any results were found
        if ($results->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No matching pending accounts found.',
                'suggestions' => [],
            ], 404);
        }

        // Prepare the response data
        $response = [
            'status' => 200,
            'message' => 'Search results fetched successfully.',
            'suggestions' => $results,
        ];

        // Return the JSON response
        return response()->json($response);
    }

    public function acceptPendingAccounts(Request $request)
    {
        // Validate the request
        $request->validate([
            'id' => 'required|integer|exists:doctor_and_workers,id', // Ensure the ID exists in the table
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Find the pending account
            $pendingAccount = DoctorAndWorker::find($request->input('id'));

            // Check if the account is already accepted
            if ($pendingAccount->account_request === false) {
                return response()->json([
                    'status' => 400,
                    'message' => 'This account request has already been accepted.',
                ], 400);
            }

            // Create a new user in the User model
            $randomPswCred = rand(1000, 9999);

            $user = User::create([
                'name' => $pendingAccount->name,
                'email' => $pendingAccount->email,
                'password' => bcrypt($randomPswCred), // Hash the password for login
                'pswCred' => $randomPswCred, // Store plain 4-digit password for retrieval
                'role' => 'user',
                'unique_user_id' => $this->generateUniqueUserId(),
            ]);

            // Associate the user with the DoctorAndWorker record
            $pendingAccount->update([
                'user_id' => $user->id,
                'account_request' => false,
            ]);

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json([
                'status' => 200,
                'message' => 'Account request accepted successfully.',
                'user' => $user, // Return the created user details (optional)
            ]);

        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Return an error response
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while accepting the account request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deletePendingAccount($id)
    {
        try {
            // Find the doctor/worker record
            $doctorWorker = DoctorAndWorker::find($id);

            if (!$doctorWorker) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Doctor/Worker record not found.',
                ], 404);
            }

            // Delete the doctor/worker record
            $doctorWorker->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Account request deleted successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while deleting the account request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ends here 


    public function addDoctor(Request $request){
         
        DB::beginTransaction(); 

        $validator = Validator::make($request->all(), [
            'profession' => 'required|string',
            'name' => 'required|string',
            // 'age' => 'required|integer',
            // 'sex' => 'required|string',
            // 'relativeName' => 'required|string',
            'phone' => 'required|numeric|digits_between:1,10',  
            'email' => 'required|email|unique:users,email',
            // 'registrationNo' => 'required|string',
            // 'village' => 'required|string',
            // 'po' => 'required|string',  // Post Office
            // 'ps' => 'required|string',  // Police Station
            // 'pin' => 'required|string',  // Postal Code (string for flexibility)
            // 'district' => 'required|string',
            // 'buildingNo' => 'required|string',
            // 'landmark' => 'required|string',
            'workDistrict' => 'required|string',
            // 'state' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json(['validation_error'=> $validator->messages()]);
        }


        try{

            $docAccount = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'), // plain text based psw client requirement 
                'pswCred' => $request->input('pswCred'), // for retrieval
                'role' => 'user',
                'unique_user_id' => $this->generateUniqueUserId(),
            ]);

            if($docAccount){

                $doctorUserData = DoctorAndWorker::create([
                        'name' => $docAccount->name,
                        'user_type' => $docAccount->user_type,
                        'user_id' => $docAccount->id,  // Foreign key
                        'age' => $request->input('age'),
                        'sex' => $request->input('sex'),
                        'relativeName' => $request->input('relativeName'),
                        'phone' => $request->input('phone'),
                        'email' => $docAccount->email,
                        'registrationNo' => $request->input('registrationNo'),
                        'village' => $request->input('village'),
                        'po' => $request->input('po'),
                        'ps' => $request->input('ps'),
                        'pin' => $request->input('pin'),
                        'district' => $request->input('district'),
                        'buildingNo' => $request->input('buildingNo'),
                        'landmark' => $request->input('landmark'),
                        'workDistrict' => $request->input('workDistrict'),
                        'state' => $request->input('state'),
                        'designation' => $request->input('profession'),
                        'unique_user_id' =>  $docAccount->unique_user_id,
                ]);

                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => 'Account created successfully',
                    'docData' => [$docAccount, $doctorUserData],
                ]);
            
            }


        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'error' => 'Database error: ' . $e->getMessage(),
                'message' => 'Please try registering with a unique email or Phone number, one already exist'
            ]); 
        }   
    }


    public function viewDoctor(Request $request) {
        try {
           
            $recordsPerPage = $request->query('recordsPerPage', 10);
    
            // Fetch paginated data from the model
            $doctorsData = DoctorAndWorker::where('account_request', '!=', 'pending')
                ->where('disable_status', '!=', '1')
                ->paginate($recordsPerPage);
    
            if ($doctorsData->isEmpty()) {
                return response()->json([
                    'status' => 204,
                    'message' => 'No user found',
                ]);
            }
    
            return response()->json([
                'status' => 200,
                'listData' => $doctorsData->items(), // Return the paginated items
                'message' => 'Total user data found: ' . $doctorsData->total(), // Use total count
                'total' => $doctorsData->total(),
                'current_page' => $doctorsData->currentPage(),
                'last_page' => $doctorsData->lastPage(),
                'per_page' => $doctorsData->perPage(),
            ]);
    
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 500,
                'message' => 'Failed to fetch user data',
            ]);
        }
    }


    public function fetchSingleDoctor($id, request $request){

        try{
            $doctorsData = DoctorAndWorker::where('id', $id)->get();

            if ($doctorsData->isEmpty()) {
                return response()->json([
                    'status' => 204,
                    'message' => 'No User found',
                ]);
            }

            return response()->json([
                'status' => 200,
                'doc_data' => $doctorsData,
                'message' => 'Total Users data found: ' . $doctorsData->count(),
            ]);

        }catch(Exception $e){

            return response()->json([
                'error' => $e->getMessage(),
                'status' => 500,
                'message' => 'failed to fetch User data',
            ]);
        }
    }


    public function fetchDoctorsCred($id){

        try{

            $doctor = User::select('name', 'email', 'pswCred')->find($id);

            if ($doctor) {
                return response()->json([
                    'status' => 200,
                    'success' => true,
                    'data' => $doctor
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => 'error on fetching user credentials',
                'error' => $e->getMessage(),
            ]);
        }

    }


    public function changeDocPsw($id, Request $request){

        try {
                $newPsw = User::where('id', $id)->update([
                    'password' => bcrypt($request->input('pswCred')),
                    'pswCred' => $request->input('pswCred'),
                ]); //

                if($newPsw){
                    return response()->json([
                        'status' => 200,
                        'success' => true,
                        'message' => 'Password updated successfully'
                    ]);
                }else{
                    return response()->json([
                        'status' => 404,
                        'success' => false,
                        'message' => 'User not found'
                    ]);
                }
    
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error updating password, Contact developer',
                'error' => $e->getMessage(),
            ]);
        }

       
       
    }


    public function updateDoctorData($id, Request $request) {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|',
            'phone' => 'required|'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->messages()]);
        }
    
        DB::beginTransaction(); 

        try{

            $userAccountData = DoctorAndWorker::find($request->id);

            $userAccountData->update([
                // not updating email since its in 2 different tables - not imp
                    'name' => $request->input('name'),
                    'age' => $request->input('age'),
                    'phone' => $request->input('phone'),
                
                    'sex' => $request->input('sex'),
                    'relativeName' => $request->input('relativeName'),
                    'registrationNo' => $request->input('registrationNo'),
                    'village' => $request->input('village'),

                    'po' => $request->input('po'),
                    'ps' => $request->input('ps'),
                    'pin' => $request->input('pin'),
                    'district' => $request->input('district'),

                    'buildingNo' => $request->input('buildingNo'),
                    'landmark' => $request->input('landmark'),
                    'workDistrict' => $request->input('workDistrict'),
                    'state' => $request->input('state'),

                ]);

            
            if($userAccountData){

                // not updating email since its in 2 different tables - not imp
            
                $user =  User::where('id', $request->user_id)->first();
            
                $user->update([
                        'name' => $request->input('name'),
                        'designation' => $request->input('profession'),
                ]);

                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => 'Account updated Successfully',
                ]);
            } 

        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Fatal Error during update, please register the user again',
                'error' => $e->getMessage(),
            ]);
        }
        

    
    }


    public function disableDoctorData($id) {
        DB::beginTransaction();
    
        try {
            $docWorkData = DoctorAndWorker::find($id);
    
            // Check if the doctor data exists
            if (!$docWorkData) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Doctor data not found'
                ]);
            }
    
            // Update the doctor's disable status
            $docDataUpdated = $docWorkData->update([
                'disable_status' => 1,
            ]);
    
            // Check if the doctor data update was successful
            if ($docDataUpdated) {
                // Fetch the associated user data
                $userData = User::where('id', $docWorkData->user_id);
    
                // Update the user's disable status
                $updated = $userData->update([
                    'disable_status' => 1,
                ]);
    
                // Check if the user data update was successful
                if ($updated) {
                    DB::commit();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Item disabled successfully'
                    ]);
                } else {
                    // If the user update fails, roll back and return error
                    DB::rollBack();
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to update user disable status'
                    ]);
                }
            } else {
                // If the doctor data update fails, roll back and return error
                DB::rollBack();
                return response()->json([
                    'status' => 500,
                    'message' => 'Failed to update doctor disable status'
                ]);
            }
    
        } catch (Exception $e) {
            // Rollback transaction and return error on exception
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error',
                'error' => $e->getMessage(),
            ]);
        }
    }

    // this function is being used to find doctor or work based on email, name, phone, location
    public function autoSearchUser(request $request){
  
        $query = $request->input('query');
        
   
        if (empty($query)) {
            return response()->json(['suggestions' => []]);
        }


        $suggestions = DoctorAndWorker::where('disable_status', '!=', '1')
        ->where(function($subQuery) use ($request) {
            $searchQuery = $request->input('query');
            $subQuery->where('phone', 'like', '%' . $searchQuery . '%')
                    ->orWhere('email', 'like', '%' . $searchQuery . '%')
                    ->orWhere('name', 'like', '%' . $searchQuery . '%')
                    ->orWhere('workDistrict', 'like', '%' . $searchQuery . '%');
        })
        ->take(10) 
        ->get(['phone', 'email', 'name', 'workDistrict', 'id']);

        return response()->json(['suggestions' => $suggestions]);
    }
    


    // controller specific helpers 

    //helper function to double check and generate a unique user id for every account
    private function generateUniqueUserId() {
        do {
            $uniqueUserId = 'USER-' . strtoupper(uniqid()); // Generating a new unique ID
        } while (User::where('unique_user_id', $uniqueUserId)->exists()); // Checking for uniqueness
        
        return $uniqueUserId; 
    }


}
