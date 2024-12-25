<?php

namespace App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use App\Models\OauthTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{



    public function googleCallback(Request $request) {
        
        $googleClientId = env('GOOGLE_CLIENT_ID');
        $clientSecret = env('GOOGLE_CLIENT_SECRET');
        $redirectUri = env('GOOGLE_REDIRECT_URL');
        $code = $request->code;
        


        // Check if the code is present in the request
        if (empty($code)) {
            return response()->json(['error' => 'Missing authorization code'], 400);
        }

        // Request Google's token endpoint
        $response = Http::asForm()
            ->withoutVerifying()
            ->post('https://oauth2.googleapis.com/token', [
                'grant_type'    => 'authorization_code',
                'client_id'     => $googleClientId,
                'client_secret' => $clientSecret,
                'redirect_uri'  => $redirectUri,
                'code'          => $code,
            ]);

        if ($response->successful()) {
            $tokens = $response->json();

            // Fetch user profile data
            $userResponse = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . $tokens['access_token'],
            ])->get('https://www.googleapis.com/oauth2/v1/userinfo');

            if ($userResponse->successful()) {
                $userInfo = $userResponse->json();

                $personalAccessToken = $this->storeUserAndToken($userInfo, $tokens);

                return response()->json([
                    'access_token' => $tokens['access_token'],
                    'refresh_token' => $tokens['refresh_token'],
                    'personal_access_token' => $personalAccessToken, // Return personal access token
                    'expires_in' => $tokens['expires_in'],
                    'scope' => $tokens['scope'],
                    'user_profile' => $userInfo, // Include user profile data
                ]);
            } else {
                return response()->json(['error' => 'Failed to fetch user profile'], 500);
            }
        }

        // If the response fails, return an error message
        return response()->json(['error' => 'Failed to obtain access token', 'details' => $response->json()], 400);
    }



    public function storeUserAndToken($userInfo, $tokens) {
        
        // Store or update user information
        $user = User::updateOrCreate(
            ['email' => $userInfo['email']],
            [
                'name' => $userInfo['name'],
                'role' => 'admin', // Set role to admin
            ]
        );


        // return response()->json($userInfo);
        // Store or update OAuth token information
        OauthTable::updateOrCreate(
            ['user_id' => $user->id],
            [
                'refresh_token' => $tokens['refresh_token'],
                'profile_link' => $userInfo['picture'] ?? null,
            ]
        );

        // Generate a personal access token for the user
        // Check if a token already exists
        if ($user->tokens()->exists()) {
            $user->tokens()->delete(); // Delete existing tokens     
        }

        // Create a new personal access token
        $personalAccessToken = $user->createToken('Personal Access Token');
        return $personalAccessToken->plainTextToken;
    }
}
