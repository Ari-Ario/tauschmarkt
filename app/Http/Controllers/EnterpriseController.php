<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Favorites;
use App\Http\Resources\UserResource;

class EnterpriseController extends Controller
{
    //
    // public function latestEnterprises($user_id)
    // {

    //     // Fetch users with their favorite details in a single query
    //     $enterprises = User::leftJoin('favorites', function($join) use ($user_id) {
    //         $join->on('users.id', '=', 'favorites.first_user_id')
    //              ->where('favorites.user_id', '=', $user_id);
    //     })
    //     ->select('users.*', 'favorites.first_user_id', 'favorites.is_favorite')
    //     ->where('users.is_seller', 1)
    //     ->latest()
    //     ->get();
    
    //     // Map user data and merge favorite details
    //     $users = $enterprises->map(function ($user) {
    //         return array_merge($user->toArray(), [
    //             'first_user_id' => $user->first_user_id,
    //             'user_id' => $user->first_user_id,
    //             'is_favorite' => $user->is_favorite,
    //         ]);
    //     });
    
    //     return response()->json($users);

    // }

    public function latestEnterprises(Request $request, $user_id)
    {
        // Fetch latitude and longitude from the request
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $distance = $request->input('distance'); // Distance in kilometers

        // Haversine formula to calculate distance
        $haversine = "(6371 * acos(cos(radians($latitude)) 
                        * cos(radians(users.latitude)) 
                        * cos(radians(users.longitude) - radians($longitude)) 
                        + sin(radians($latitude)) 
                        * sin(radians(users.latitude))))";

        // Fetch users within the specified distance and with their favorite details in a single query
        $enterprises = User::leftJoin('favorites', function($join) use ($user_id) {
            $join->on('users.id', '=', 'favorites.first_user_id')
                ->where('favorites.user_id', '=', $user_id);
        })
        ->select('users.*', 'favorites.first_user_id', 'favorites.is_favorite')
        ->where('users.is_seller', 1)
        ->whereRaw("$haversine < ?", [$distance])
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
