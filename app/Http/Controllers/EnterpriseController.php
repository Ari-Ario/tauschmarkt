<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Favorits;
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

    public function updateFavorite(Request $request)
    {
        $enterprise = Favorits::findOrFail($request->enterpriseId);
        $enterprise->is_favorite = $request->isFavorite;
        $enterprise->save();

        return response()->json(['message' => 'Favorite status updated successfully']);
    }
}
