<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Http\Resources\ProvinceResource;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends ApiController
{
    public function getProvinces()
    {
        $provinces = Province::all();
        return $this->succesResponse('all provinces', 200, ProvinceResource::collection($provinces));
    }

    public function getCities()
    {
        $cities = City::all();
        return $this->succesResponse('all cities', 200, CityResource::collection($cities));
    }
}
