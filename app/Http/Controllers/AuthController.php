<?php

namespace App\Http\Controllers;

use App\Http\Resources\Front\UserResource;
use App\Models\User;
use App\Notifications\OTPSms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cellphone' => ['nullable', 'regex:/^(\+98|0)?9\d{9}$/'],
            'email' => ['nullable', 'email',]
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        if($request->has('cellphone'))
        {
            $user = User::where('cellphone', $request->cellphone)->first();
        }else {
            $user = User::where('email', $request->email)->first();
        }
        $OTPCode = mt_rand(100000, 999999);
        $loginToken = Hash::make('DCDCojncd@cdjn%!!ghnjrgtn&&');

        if ($user) {
            $user->update([
                'otp' => $OTPCode,
                'login_token' => $loginToken
            ]);
        } else {
            if($request->has('cellphone'))
            {
                $user = User::Create([
                    'cellphone' => $request->cellphone,
                    'otp' => $OTPCode,
                    'login_token' => $loginToken
                ]);
            }else{
                $user = User::Create([
                    'email' => $request->email,
                    'otp' => $OTPCode,
                    'login_token' => $loginToken
                ]);
            }
        }
        $user->notify(new OTPSms($OTPCode));

        return $this->succesResponse('user is logged in',200,['login_token' => $loginToken]);
    }

    public function checkOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:6',
            'login_token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $user = User::where('login_token', $request->login_token)->firstOrFail();

        if ($user->otp == $request->otp) {
            $token = $user->createToken('myApp', ['user'])->plainTextToken;

            return $this->succesResponse('user created successfully',200,[
                'user' => new UserResource($user),
                'token' => $token
            ]);
        } else {
            return $this->errorResponse('کد ورود نادرست است', 422);
        }
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $user = User::where('login_token', $request->login_token)->firstOrFail();
        $OTPCode = mt_rand(100000, 999999);
        $loginToken = Hash::make('DCDCojncd@cdjn%!!ghnjrgtn&&');

        $user->update([
            'otp' => $OTPCode,
            'login_token' => $loginToken
        ]);

        $user->notify(new OTPSms($OTPCode));

        return $this->successResponse(['login_token' => $loginToken], 200);
    }

    public function me()
    {
        $user = User::find(auth()->id());
        return $this->succesResponse('your requested user', 200, new UserResource($user));
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->succesResponse('user logged out',200, []);
    }
}
