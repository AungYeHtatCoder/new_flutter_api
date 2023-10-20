<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Log;
use App\Models\User;
Use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'user' => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'user' => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    //public function login(LoginRequest $request)
    //{
        //  $credentials = $request->validated();
        // if (!Auth::attempt($credentials)) {
        //     return response([
        //         'message' => 'Provided email or password is incorrect'
        //     ], 422);
        // }
        // /** @var \App\Models\User $user */
        // $user = Auth::user();
        // $token = $user->createToken('main')->plainTextToken;
        // return response(compact('user', 'token'));
        //return response(['message' => 'This is a test']);
   // }

    // public function logout()
    // {
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();
    //     $user->tokens()->delete();
    //     return response([
    //         'message' => 'Logged out'
    //     ]);
    // }


    // public function logoutUser(Request $request)
    // {
    //     // Revoke the token that was used to authenticate the current request
    //     $request->user()->currentAccessToken()->delete();

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Logged out successfully'
    //     ]);
    // }

//     public function logoutUser(Request $request)
// {
//     \Log::info('User:', [$request->user()]);
//     \Log::info('Token:', [$request->user()->currentAccessToken()]);

//     // Revoke the token that was used to authenticate the current request
//     $request->user()->currentAccessToken()->delete();

//     return response()->json([
//         'status' => 'success',
//         'message' => 'Logged out successfully'
//     ]);
// }

public function logoutUser(Request $request)
{
    $token = $request->user()->currentAccessToken();
    
    if ($token && method_exists($token, 'delete')) {
        $token->delete();
    } elseif ($token) {
        // This is a transient token, handle accordingly
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'No current token found'
        ]);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Logged out successfully'
    ]);
}

}