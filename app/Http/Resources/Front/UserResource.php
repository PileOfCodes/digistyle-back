<?php

namespace App\Http\Resources\Front;

use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $pid = City::where('id',$this->address->city_id)->value('province_id');
        return [
            "id" => $this->id,
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
            "email" => $this->email,
            "cellphone" => $this->cellphone,
            "national_code" => $this->address->national_code,
            "birthday" => verta($this->address->birthday)->format('%B %d، %Y'),
            "sex" => $this->address->sex,
            "city_code" => $this->address->city_code,
            "phone" => $this->address->phone,
            "city_name" => City::where('id',$this->address->city_id)->value('name'),
            "province_name" => Province::where('id', $pid)->value('name'),
            "address" => $this->address->address,
            "address_id" => $this->address->id,
            "postal_code" => $this->address->postal_code,
            "mobile" => $this->address->mobile,
            "receiver_name" => $this->address->receiver_name,
            "residence" => $this->address->residence,

        ];
    }
}
