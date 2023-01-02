<?php

namespace App\Http\Controllers\Authentication;

use Illuminate\Routing\Controller;
use App\Models\Auth\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rules\Password as RulesPassword;

class AuthController extends Controller
{
    public function userRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'same:password_confirmation', RulesPassword::min(6)->mixedCase()],
            'role_id' => 'required'
        ]);

        try {

            $user = new User();
            $user->name =  $request->name;
            $user->email =  $request->email;
            $user->role_id =  $request->role_id;
            $user->password =  Crypt::encrypt($request->userPassword);
            $user->status =  json_decode($request->status);

            if ($user->save()) {
                $ability = [];
                if ($user->role_id === 1) {
                    $ability = ['*'];
                } else if ($user->role_id === 2) {
                    $ability = ['server:update'];
                } else {
                    $ability = ['server:update'];
                }

                $expired = 7 * 24 * 60; //minutes
                $token = $user->createToken($user->email, $ability)->plainTextToken;

                return response([
                    'token' => $token,
                    'user' => $user,
                    'message' => 'Registration successfull',
                    'status' => 'success'
                ], 201);
            }
        } catch (Exception $error) {
            return response([
                'message' => $error,
                'status' => 'failed'
            ], 401);
        }
    }


    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if ($user && Crypt::decrypt($user->password) === $request->password) {

                $ability = [];
                if ($user->role_id === 1) {
                    $ability = ['*'];
                } else if ($user->role_id === 2) {
                    $ability = ['server:update'];
                } else {
                    $ability = ['server:update'];
                }


                $expired = 7 * 24 * 60; //minutes
                $token = $user->createToken($user->email, $ability)->plainTextToken;

                return response([
                    'token' => $token,
                    'user' => $user,
                    'message' => 'Login successfull',
                    'status' => 'success'
                ], 200);
            } else {
                return response([
                    'message' => 'Authentication failed',
                    'status' => 'failed'
                ], 401);
            }
        } catch (Exception $error) {
            return response([
                'message' => $error,
                'status' => 'failed'
            ], 401);
        }
    }



    public function userLogout()
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logout Successfull',
            'status' => 'success'
        ], 200);
    }


    public function LoggedUser()
    {
        $user = auth()->user();

        return response([
            'user' => $user,
            'message' => 'Logout Successfull',
            'status' => 'success'
        ], 200);
    }

    public function changePassword(Request $request)
    {

        $request->validate([
            'password' => ['required', 'same:password_confirmation', RulesPassword::min(6)->mixedCase()]
        ]);

        $user = auth()->user();
        $user->password = Crypt::encrypt($request->password);

        return response([
            'message' => 'Password Changed',
            'status' => 'success'
        ], 200);
    }
}
