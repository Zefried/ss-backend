<?php

namespace App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminLogin extends Controller
{

    public function adminLogin(Request $request)
    {

        try{

            $validator = Validator::make($request->all(), [
                'email' => 'required|',
                'password' => 'required|',
            ]);
    
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => '400',
                    'validation_error' => $validator->messages(),
                ]);
            }

            $credentials = $request->only('email', 'password');

            // Check if the user exists
            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid email or password',
                ]);
            }

            // Revoking all previous tokens (optional)
            $user->tokens()->delete();

            if($user->role === 'admin'){
                // Creating a new token for the user with admin ability
                $token = $user->createToken('auth_token', ['admin'])->plainTextToken;
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'You are not authorized to access this route',
                ]);
            }
           
            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user, 
                'role' => $user->role,
            ], 200);

        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => 'fatal error during login',
                'error' => $e->getMessage(),
            ]);
        }
       
    }


    public function userLogin(Request $request)
    {

        try{

            $validator = Validator::make($request->all(), [
                'email' => 'required|',
                'password' => 'required|',
            ]);
    
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => '400',
                    'validation_error' => $validator->messages(),
                ]);
            }

            $credentials = $request->only('email', 'password');

            // Check if the user exists
            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid email or password',
                ]);
            }

            // Revoking all previous tokens (optional)
            $user->tokens()->delete();

            if($user->role === 'user'){
                // Creating a new token for the user with admin ability
                $token = $user->createToken('auth_token', ['user'])->plainTextToken;
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'You are not authorized to access this route',
                ]);
            }
           
            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user, 
                'role' => $user->role,
            ], 200);

        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => 'fatal error during login',
                'error' => $e->getMessage(),
            ]);
        }
       
    }


    public function labLogin(Request $request) 
    {
        try {
            // Validate the incoming request using labID instead of email
            $validator = Validator::make($request->all(), [
                'labID' => 'required|email',
                'password' => 'required',
            ]);
    
            // If validation fails, return the validation errors
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'validation_error' => $validator->messages(),
                ]);
            }
    
            // Get the credentials from the request
            $credentials = $request->only('labID', 'password');
    
            // Check if the user exists based on the labID (email in this case)
            $user = User::where('email', $credentials['labID'])->first();
    
            // If user doesn't exist or password doesn't match, return error
            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid email or password',
                ]);
            }
    
            // Optional: Revoke all previous tokens
            $user->tokens()->delete();
    
            // Check if the user role is 'lab'
            if ($user->role === 'lab') {
                // Create a new token for the lab user
                $token = $user->createToken('auth_token', ['lab'])->plainTextToken;
            } else {
                // If the user is not authorized, return an error
                return response()->json([
                    'status' => 401,
                    'message' => 'You are not authorized to access this route',
                ]);
            }
    
            // Return a success response with the token and user info
            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
                'role' => $user->role,
            ], 200);
    
        } catch (Exception $e) {
            // If any error occurs, return a fatal error response
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error during login',
                'error' => $e->getMessage(),
            ]);
        }
    }
    


    public function hospitalLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'hospitalID' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'validation_error' => $validator->messages(),
                ]);
            }

            $credentials = $request->only('hospitalID', 'password');

            // Check if the hospital exists
            $hospital = User::where('email', $credentials['hospitalID'])->first();

            if (!$hospital || !Hash::check($credentials['password'], $hospital->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid hospital ID or password',
                ]);
            }

            // Revoke all previous tokens (optional)
            $hospital->tokens()->delete();

            // Ensure only hospital role can log in
            if ($hospital->role === 'hospital') {
                $token = $hospital->createToken('auth_token', ['hospital'])->plainTextToken;
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'You are not authorized to access this route',
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                'token' => $token,
                'hospital' => $hospital,
                'role' => $hospital->role,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Fatal error during login',
                'error' => $e->getMessage(),
            ]);
        }
    }


}
