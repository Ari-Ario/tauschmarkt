<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Stripe\StripeClient;
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
                ? asset('storage/' .$user->profile_picture)
                : asset('storage/profile_images/default.png'),
            'enterprise_picture' => $user->enterprise_picture
                ? asset('storage/' . $user->enterprise_picture)
                : asset('storage/default.png'),
            'street' => $user->street,
            'house_number' => $user->house_number,
            'city' => $user->city,
            'zip_code' => $user->zip_code,
            'payment' => $user->payment,
            'opening' => $user->opening,
            'closing' => $user->closing,
            'latitude' => $user->latitude,
            'longitude' => $user->longitude,
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
                'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
                'street' => 'nullable|string|max:255',
                'house_number' => 'nullable|string|max:255',
                'zip_code' => 'nullable|string|max:20',
                'city' => 'nullable|string|max:255',
                'payment' => 'nullable|string', // Assuming 'payment' is the IBAN
                'opening' => ['nullable', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9](?::[0-5][0-9])?$/'], // HH:MM format
                'closing' => ['nullable', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9](?::[0-5][0-9])?$/'],
            ]);
        
            // Convert HH:MM to HH:MM:SS format before updating
            // if ($request->has('opening')) {
            //     $request->merge(['opening' => $request->input('opening') . ':00']);
            // }
            // if ($request->has('closing')) {
            //     $request->merge(['closing' => $request->input('closing') . ':00']);
            // }
        
            // Update user profile
            $user->update($request->only([
                'firstname',
                'lastname',
                'email',
                'street',
                'house_number',
                'zip_code',
                'city',
                'opening',
                'closing',
            ]));
        
            // Check if IBAN (payment) is provided
            if ($request->has('payment') && !empty($request->payment)) {
                $stripe = new StripeClient('sk_test_51PRVFGCFV0u7TeyeT35q849Bj5Z20yEOr2EoFcRvJyW7ELi7BmxiDfzPhcggYibOAqCIoal1J0vuHX0iJ3RVVFnL00o4IPXSbH');
        
                try {
                    if (!$user->stripe_account_id) {
                        // Create a new Stripe account
                        $account = $stripe->accounts->create([
                            'type' => 'custom',
                            'country' => 'CH', // Switzerland
                            'email' => $user->email,
                            'business_type' => 'individual', // Assuming individual, you can change to company if needed
                            'individual' => [
                                'first_name' => $user->firstname,
                                'last_name' => $user->lastname,
                                'email' => $user->email,
                                'address' => [
                                    'line1' => $user->street . ' ' . $user->house_number,
                                    'postal_code' => $user->zip_code,
                                    'city' => $user->city,
                                    'country' => 'CH'
                                ]
                            ],
                            'capabilities' => [
                                'transfers' => ['requested' => true],
                            ],
                        ]);
        
                        $user->stripe_account_id = $account->id;
                        $user->save(); // Save the stripe_account_id immediately
                    } else {
                        // Retrieve existing Stripe account
                        $account = $stripe->accounts->retrieve($user->stripe_account_id);
                    }
        
                    // Update account with IBAN
                    $externalAccount = $stripe->accounts->createExternalAccount(
                        $user->stripe_account_id,
                        [
                            'external_account' => [
                                'object' => 'bank_account',
                                'country' => 'CH', // Switzerland
                                'currency' => 'chf', // Swiss Franc
                                'account_holder_name' => $user->firstname . ' ' . $user->lastname,
                                'account_holder_type' => 'individual',
                                'account_number' => $request->payment,
                            ],
                        ]
                    );
        
                    // Debugging output
                    info('External account created:', ['external_account' => $externalAccount]);
        
                    // Save the Stripe account ID in the stripe_account_id field
                    $user->save();
                } catch (ApiErrorException $e) {
                    return response()->json(['message' => 'Error linking Stripe account: ' . $e->getMessage()], 500);
                }
            }
        
            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user,
            ]);
        }

        public function updateUserLocation(Request $request)
        {
            // return $request;

            $user = Auth::user();

            $request->validate([
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            $user->update($request->only([
                'latitude',
                'longitude',
            ]));
    
            return response()->json([
                'message' => 'Location updated successfully',
            ]);
        }

        ////////////////////////////////////////Upload Background- & Profile-Images //////////////////////////////
        public function updateBackgroundImage(Request $request)
        {
            $request->validate([
                'enterprise_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
            $user = auth()->user();

            $oldImage = $user->enterprise_picture;
            // $oldImagePath->delete();

            if ($oldImage) {
                $oldImagePath = public_path('storage/' . $oldImage);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
    
            $file = $request->file('enterprise_picture');
            $path = $file->store('enterprise_images', 'public');
    
            // Update the user's profile with the new image path
            $user->enterprise_picture = $path;
            $user->save();
    
            return response()->json(['path' => $path], 200);
        }

        
        public function updateProfileImage(Request $request)
        {
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
            $user = auth()->user();
            $oldImage = $user->profile_picture;

            if ($oldImage) {
                $oldImagePath = public_path('storage/' . $oldImage);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
    
            $file = $request->file('profile_picture');
            $path = $file->store('profile_images', 'public');
    
            // Update the user's profile with the new image path
            $user->profile_picture = $path;
            $user->save();
    
            return response()->json(['path' => $path], 200);
        }

}
