<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function updateFavorite(Request $request)
    {
        $enterprise = Favorit::findOrFail($request->enterpriseId);
        $enterprise->is_favorite = $request->isFavorite;
        $enterprise->save();

        return response()->json(['message' => 'Favorite status updated successfully']);
    }
    //
}
