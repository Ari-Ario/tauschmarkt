<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{


    public function show(){
        // $user = User::findOrFail(auth()->id());

        return new UserResource(User::findOrFail(auth()->id()));
    }

    public function getUserProfile($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $dateFormat = 'Y-m-d H:i:s'; // or your desired date format

        return [
            // 'id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            // 'is_seller' => $user->is_seller,
            'profile_picture' => $user->profile_picture
                ? asset($user->profile_picture)
                : asset('storage/profile_images/default.png'),
            'enterprise_picture' => $user->enterprise_picture
                ? asset('storage/enterprise_images/' . $user->enterprise_picture)
                : asset('storage/enterprise_images/default.png'),
            'street' => $user->street,
            'house_number' => $user->house_number,
            'city' => $user->city,
            'zip_code' => $user->zip_code,
            // 'latitude' => $user->latitude,
            // 'longitude' => $user->longitude,
            'created_at' => optional($user->created_at)->format($dateFormat),
            // 'updated_at' => optional($user->updated_at)->format($dateFormat),
        ];

    }
        //////////////////////////////////////// Update user profile data //////////////////////////////

        public function updateUserProfile(Request $request)
        {
            $user = Auth::user();

            $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'street' => 'string|max:255',
                'house_number' => 'string|max:255',
                'zip_code' => 'string|max:20',
                'city' => 'string|max:255',
            ]);
    
            $user->update($request->only([
                'firstname',
                'lastname',
                'email',
                'street',
                'house_number',
                'zip_code',
                'city',
            ]));
    
            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user,
            ]);
        }

        ////////////////////////////////////////Upload Background- & Profile-Images //////////////////////////////
        public function updateBackgroundImage(Request $request)
        {
            $request->validate([
                'enterprise_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            $file = $request->file('enterprise_picture');
            $path = $file->store('enterprise_images', 'public');
    
            // Update the user's profile with the new image path
            $user = auth()->user();
            $user->enterprise_picture = $path;
            $user->save();
    
            return response()->json(['path' => $path], 200);
        }

        
        public function updateProfileImage(Request $request)
        {
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            $file = $request->file('profile_picture');
            $path = $file->store('profile_images', 'public');
    
            // Update the user's profile with the new image path
            $user = auth()->user();
            $user->profile_picture = $path;
            $user->save();
    
            return response()->json(['path' => $path], 200);
        }

}
