<?php

namespace App\Http\Controllers;

use App\Http\Resources\Front\AddressResource;
use App\Http\Resources\Front\UserResource;
use App\Models\Address;
use App\Models\City;
use App\Models\Province;
use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfileController extends ApiController
{
    public function userInfo(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "firstname" => ['required','string'],
            "lastname" => ['required', 'string'],
            "email" => ['required','email'],
            "cellphone" => ['required'],
            "national_code" => ['nullable'],
            "day" => ['required'],
            "month" => ['required'],
            "year" => ['required'],
            "sex" => ['required'],
            "city_id" => ['required']
        ]);
        if($validate->fails())
        {
            return $this->errorResponse($validate->messages(), 422);
        }
        DB::beginTransaction();
        $user = User::where('id', auth()->id())->update([
            "firstname" => $request->firstname,
            "lastname" => $request->lastname,
            "email" => $request->email,
            "cellphone" => $request->cellphone
        ]);
        
        $numbers = Verta::jalaliToGregorian($request->year,$request->month,$request->day);
        $date = Carbon::createFromDate($numbers[0],$numbers[1],$numbers[2]);
        $city = City::where('id', $request->city_id)->first();
        $province = Province::where('id', $request->province_id)->first();
        $residence = "$province->name - $city->name";

        $address = Address::updateOrCreate([
            'user_id' => Auth::user()->id
        ],[
            "sex" => $request->sex,
            "birthday" => $date->toDate(),
            "residence" => $residence,
            "national_code" => $request->has('national_code') ? $request->national_code : null
        ]);
        DB::commit();
        return $this->succesResponse('user info', 200, new UserResource($user));
    }

    public function createAddress(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "receiver_name" => 'required',
            "mobile" => 'required',
            "postal_code" => 'required',
            "city_code" => 'nullable',
            "address" => 'required',
        ]);
        if($validate->fails())
        {
            return $this->errorResponse($validate->messages(), 422);
        }
        DB::beginTransaction();
        $city = City::where('id', $request->city_id)->first();
        $province = Province::where('id', $request->province_id)->first();
        $residence = "$province->name - $city->name";

        $address = Address::create([
            'user_id' => Auth::user()->id,
            "receiver_name" => $request->receiver_name,
            "mobile" => $request->mobile,
            "province_id" => $request->province_id,
            "city_id" => $request->city_id,
            "address" => $request->address,
            "residence" => $residence,
            "postal_code" => $request->postal_code,
            "phone" => $request->has('phone') ? $request->phone : null,
            "city_code" => $request->has('city_code') ? $request->city_code : null
        ]);
        DB::commit();
        return $this->succesResponse('user address', 200, new UserResource($address));
    }


    public function editAddress(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "receiver_name" => ['required','string'],
            "mobile" => ['required'],
            "postal_code" => ['required'],
            "city_code" => ['nullable'],
            "address" => ['required'],
        ]);
        if($validate->fails())
        {
            return $this->errorResponse($validate->messages(), 422);
        }
        DB::beginTransaction();
        $address = Address::where('id', $request->address_id)->first();
        $city = City::where('id', $request->city_id)->first();
        $province = Province::where('id', $request->province_id)->first();
        $residence = "$province->name - $city->name";
        $address->update([
            "receiver_name" => $request->receiver_name,
            "mobile" => $request->mobile,
            "phone" => $request->has('phone') && $request->phone != null ? $request->phone : $address->phone,
            "city_code" => $request->has('city_code') && $request->city_code != null ? $request->city_code : $address->city_code,
            "province_id" => $request->province_id,
            "city_id" => $request->city_id,
            "address" => $request->address,
            "residence" => $residence,
            "postal_code" => $request->postal_code,
        ]);
        DB::commit();
        return $this->succesResponse('user address edit', 200, new AddressResource($address));
    }

    public function getAddresses()
    {
        $addresses = Address::where('user_id',Auth::user()->id)->get();
        return $this->succesResponse('user addresses', 200, AddressResource::collection($addresses));
    }

    public function deleteAddress(Request $request)
    {
        DB::beginTransaction();
        $address = Address::where('id', $request->address_id)->first();
        $address->delete();
        DB::commit();
        return $this->succesResponse('user address deleted successfully', 200, new AddressResource($address));
    }

    
}
