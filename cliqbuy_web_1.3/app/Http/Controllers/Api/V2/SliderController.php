<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\SliderCollection;

class SliderController extends Controller
{
    public function index()
    {
        $home_slider_images = get_setting('home_slider_images') ?? "[]";
        return new SliderCollection(json_decode($home_slider_images, true));
    }
}
