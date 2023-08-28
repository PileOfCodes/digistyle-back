<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Front\NavbarBrandsResource;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NavbarBrandController extends ApiController
{
    public function navbarBrands()
    {
        $carbon = new Carbon();
        $newBrands = Brand::whereDate('created_at', '<=', $carbon->now()->endOfDay()->toDateString())->whereDate('created_at', '>=', $carbon->subDays(180)->startOfDay()->toDateString())->take(8)->get();
        $iranianDesigners = Brand::where('slug','koi-brand')->orWhere('slug','gray-brand')
        ->orWhere('slug','clotho-brand')->orWhere('slug','rees-brand')->get();
        return $this->succesResponse("navbar brands",200, [
            'newBrands' => NavbarBrandsResource::collection($newBrands),
            'iranianDesigners' => NavbarBrandsResource::collection($iranianDesigners),
        ]);
    }
}
