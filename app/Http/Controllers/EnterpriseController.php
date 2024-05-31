<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Favorites;
use App\Http\Resources\UserResource;

class EnterpriseController extends Controller
{
    //
    public function latestEnterprises($user_id)
    {

        // Fetch users with their favorite details in a single query
        $enterprises = User::leftJoin('favorites', function($join) use ($user_id) {
            $join->on('users.id', '=', 'favorites.first_user_id')
                 ->where('favorites.user_id', '=', $user_id);
        })
        ->select('users.*', 'favorites.first_user_id', 'favorites.is_favorite')
        ->where('users.is_seller', 1)
        ->latest()
        ->get();
    
        // Map user data and merge favorite details
        $users = $enterprises->map(function ($user) {
            return array_merge($user->toArray(), [
                'first_user_id' => $user->first_user_id,
                'user_id' => $user->first_user_id,
                'is_favorite' => $user->is_favorite,
            ]);
        });
    
        return response()->json($users);

    }


}
