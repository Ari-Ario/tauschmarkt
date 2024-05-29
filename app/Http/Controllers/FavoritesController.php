<?php

namespace App\Http\Controllers;

use App\Models\Favorites;
use App\Models\User;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    //First Method and using now
    public function getUserFavorites($userId)
    {

        $favorites = Favorites::where('user_id' , $userId)->where('is_favorite', 1)->get();


        if (!$favorites) {
            return response()->json(['message' => 'User not found'], 404);
        }
        
        // Get user details from first_user_id
        $userFavorites = $favorites->map(function($favorite) {
            $user = User::find($favorite->first_user_id);
            if ($user) {
                $dateFormat = 'd. M Y';
                return [
                    'id' => $user->id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'is_seller' => $user->is_seller,
                    'profile_picture' => $user->profile_picture
                        ? asset($user->profile_picture)
                        : asset('storage/profile_images/default.png'),
        
                    'enterprise_picture' => $user->enterprise_picture
                        ? asset('storage/enterprise_images/' . $user->enterprise_images)
                        : asset('storage/enterprise_images/default.png'),
                    'street' => $user->street,
                    'house_number' => $user->house_number,
                    'city' => $user->city,
                    'zip_code' => $user->zip_code,
                    'latitude' => $user->latitude,
                    'longitude' => $user->longitude,
        
                    'created_at' => optional($user->created_at)->format($dateFormat),
                    'updated_at' => optional($user->updated_at)->format($dateFormat),
                ];
            }
            return null;
        })->filter();

        return response()->json($userFavorites->values());
    }

    //Second Method
    public function getFavoritesByUserId($userId)
    {
        $enterprise = User::with('favorites')->get($userId);

        // $enterprise = Favorites::where('user_id' , $userId)->where('is_favorite', 1)->get();

        return response()->json($favorites);
    }

    public function updateFavorite(Request $request)
    {

        $enterprise = Favorites::where("first_user_id", $request->enterpriseId)->where('user_id', auth()->id())->firstOrFail();
        $enterprise->is_favorite = $request->isFavorite;
        $enterprise->save();

        return response()->json(['message' => 'Favorite status updated successfully']);
    }
    ////////////////////////////////////////////////Extra Methods///////////////////////////////////////////////////////////

    public function addFavorite(Request $request)
    {
        pp($request);
        $request->validate([
            'first_user_id' => 'required|numeric',
            'user_id' => 'required',
        ]);

        $favorite = Favorites::create([
            'first_user_id' => $request->first_user_id,
            'user_id' => $request->user_id,
            'is_favorite' => true,
        ]);

        return response()->json(['message' => 'Favorite added successfully', 'favorite' => $favorite]);
    }

    public function removeFavorite(Request $request)
    {
        $request->validate([
            'first_user_id' => 'required|numeric',
        ]);

        $favorite = Favorites::where('first_user_id', $request->first_user_id)
                            ->where('user_id', Auth::id())
                            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Favorite removed successfully']);
        }

        return response()->json(['message' => 'Favorite not found'], 404);
    }

        // public function getAllUsersWithFavorites()
    // {
    //     $users = User::with('favorites')->get();

    //     return response()->json($users);
    // }

}
