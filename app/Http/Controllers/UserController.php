<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use App\Http\Resources\UserResource;

class UserController extends Controller
{


    public function show(){
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
            // 'is_seller' => $user->is_seller,
            // 'profile_picture' => $user->profile_picture
            //     ? asset($user->profile_picture)
            //     : asset('storage/profile_images/default.png'),
            // 'enterprise_picture' => $user->enterprise_picture
            //     ? asset('storage/enterprise_images/' . $user->enterprise_picture)
            //     : asset('storage/enterprise_images/default.png'),
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

        // Update user profile data
        public function updateUserProfile(Request $request)
        {
            $userId = $request->input('userId');
            $key = $request->input('key');
            $value = $request->input('value');
    
            $user = User::find($userId);
    
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
    
            // Validate the key to ensure it's an allowed field
            $allowedKeys = [
                'firstname', 'lastname', 'is_seller', 'profile_picture', 
                'enterprise_picture', 'street', 'house_number', 'city', 
                'zip_code', 'latitude', 'longitude'
            ];
    
            if (!in_array($key, $allowedKeys)) {
                return response()->json(['error' => 'Invalid field'], 400);
            }
    
            $user->$key = $value;
            $user->save();
    
            return response()->json(['message' => 'Profile updated successfully']);
        }


}
