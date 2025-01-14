<?php

namespace App\Http\Controllers\AccountRegister;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountRegister extends Controller
{

    public function adminRegister(request $request){
        
        try {
   
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required|',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => '400',
                    'validation_error' => $validator->messages(),
                ]);
            }
    
            // Create a new user (admin) record
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'pswCred' => $request->pswCred,
                'password' => Hash::make($request->password), // Hash the password
            ]);
    
            // Return a success response
            return response()->json([
                'status' => 200,
                'message' => 'Admin registered successfully',
                'user' => $user
            ]);
    
        } catch (\Exception $e) {
            // Catch any exceptions and return a JSON error response
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ]);
        }
    }

    

}
