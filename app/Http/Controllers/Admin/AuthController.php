<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Admin\AdminUserResource;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function register(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required","string"],
            "email" => ["required","email","unique:users,email"],
            "password" => ["required","min:6"],
            "confirm_password" => ["required","same:password"],
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        $login_token = $user->createToken("my_token")->plainTextToken;
        DB::commit();
        return $this->succesResponse("user created successfully",200,[
            "user" => new AdminUserResource($user),
            "login_token" => $login_token
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => ["required","email"],
            "password" => ["required"]
        ]);
        if($validator->fails())
        {
            return $this->errorResponse($validator->messages(),422);
        }
        DB::beginTransaction();
        $user = User::where('email', $request->email)->firstOrFail();
        if(!$user)
        {
            return $this->errorResponse("کاربر مورد نظر پیدا نشد",422);  
        }
        if(!Hash::check($request->password,$user->password))
        {
            return $this->errorResponse("کاربر مورد نظر پیدا نشد",422);
        }
        $login_token = $user->createToken("my_token")->plainTextToken;
        DB::commit();
        return $this->succesResponse("user created successfully",200,[
            "user" => new AdminUserResource($user),
            "login_token" => $login_token
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->succesResponse("کاربر خارج شده", 200,"");
    }
}
