<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class EnterpriseController extends Controller
{
    //
    public function latestEnterprises()
    {
        $enterprise = User::latest()->take(20)->get();
        // dd($enterprise);
        return UserResource::collection($enterprise);
    }


}
