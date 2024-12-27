<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    protected $profileService;
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
        
    }
    public function getCountries(){
        return  $this->profileService->getCountries();
    }

    public function getUser(){
        return  $this->profileService->getUser();
    }

    public function updateBilling(Request $request){
       return  $this->profileService->updateBilling($request);
    }
}
