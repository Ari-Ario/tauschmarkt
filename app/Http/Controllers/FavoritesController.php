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

        $enterprise = User::with('favorites')->where('is_favorite', 1)->get($userId);

        if (!$enterprise) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($enterprise);
    }

    //Second Method
    public function getFavoritesByUserId($userId)
    {

        $favorites = Favorites::where('user_id' , $userId)->where('is_favorite', 1)->get();

        return response()->json($favorites);
    }

    public function updateFavorite(Request $request)
    {
        $enterprise = Favorites::findOrFail($request->userId);
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
