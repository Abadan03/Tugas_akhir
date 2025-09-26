<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
// use Validator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            // $success['token'] =  $user->createToken('apiToken')->plainTextToken;
            $success['name'] = $user->name;
        } catch (Exception $e) {
            return $e;
        }

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        

        // Validate the login request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Generate a Sanctum token
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            // Store the token in the session (optional)
            session(['sanctum_token' => $token]);

            // Redirect to the dashboard
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        // If authentication fails, redirect back with an error message
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
        


        // $credentials = $request->only('email', 'password');
        // if (!Auth::attempt($credentials)) {
        //     return response()->json([
        //         'message' => 'Invalid login details!'
        //     ], 401);
        // }

        // $user = User::where('email', $request->email)->firstOrFail();

        // $token = $user->createToken('auth_token')->plainTextToken;

        // return response()->json([
        //     'message' => 'Login successful',
        //     'access_token' => $token,
        //     'token_type' => 'Bearer'
        // ]);

    }

    public function logout(Request $request)
    {

        // Get the authenticated user
        $user = auth()->user();

        if ($user) {
            // Delete all tokens for the user
            $user->tokens()->delete();

            // Logout the user (invalidate the session)
            // Auth::logout();

            // Invalidate the session
            $request->session()->invalidate();

            // Regenerate the session token
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('info', 'Logout successful!');
        }

        return response()->json([
            'message' => 'User is not Authorized',
        ], 401);
    }
}
