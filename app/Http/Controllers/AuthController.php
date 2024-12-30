<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * Login user by creating an anthenticated token.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->accessToken;

            $data = [
                'token' => $token,
                'user' => $user,
            ];
            return Controller::APIJsonReturn($data, 'success');
        }

        return Controller::APIJsonReturn(['user' => 'Invalid credentials'], 'error', 401);
    }


    /**
     * Logout user by revoking his current token.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $data = [
            'user' => 'Logged out successfully'
        ];
        return Controller::APIJsonReturn($data, 'success');
    }

}
