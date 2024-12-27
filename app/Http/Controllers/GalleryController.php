<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GalleryController extends Controller
{


    public function index(){
        $maxMediaSize=config('app.MAX_MEDIA_SIZE_MB');

        return view('gallery',['maxMediaSize' => $maxMediaSize]);
    }
}
