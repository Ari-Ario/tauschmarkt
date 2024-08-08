<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Stripe\StripeClient;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
            'connect_id' => $user->stripe_connect_id,
            'payment_intent_id' => $user->stripe_payment_intent_id,
            'created_at' => optional($user->created_at)->format($dateFormat),
            // 'updated_at' => optional($user->updated_at)->format($dateFormat),
        ];

    }
        //////////////////////////////////////// Update user profile data //////////////////////////////
        
        public function updateUserProfile(Request $request)
        {
            $user = Auth::user();
        
            try {
                $validatedData = $request->validate([
                    'firstname' => 'required|string|max:255',
                    'lastname' => 'required|string|max:255',
                    'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
                    'street' => 'nullable|string|max:255',
                    'house_number' => 'nullable|string|max:255',
                    'zip_code' => 'nullable|string|max:20',
                    'city' => 'nullable|string|max:255',
                    'payment' => 'nullable|string|regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/',
                    'opening' => ['nullable', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9](?::[0-5][0-9])?$/'],
                    'closing' => ['nullable', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9](?::[0-5][0-9])?$/'],
                ]);
        
                $user->update($validatedData);
        
                if ($request->has('payment') && !empty($request->payment)) {
                    Stripe::setApiKey(config('services.stripe.secret'));
        
                    try {
                        $account = \Stripe\Account::create([
                            'type' => 'express',
                            'country' => 'CH',
                            'email' => $user->email,
                            'capabilities' => [
                                'transfers' => ['requested' => true],
                                // 'legacy_payments' => ['requested' => true],
                            ],
                            'business_type' => 'individual',
                            'individual' => [
                                'first_name' => $user->firstname,
                                'last_name' => $user->lastname,
                                'email' => $user->email,
                                'address' => [
                                    'line1' => $user->street . ' ' . $user->house_number,
                                    'postal_code' => $user->zip_code,
                                    'city' => $user->city,
                                    'country' => 'CH',
                                ],
                            ],
                        ]);
        
                        $user->stripe_connect_id = $account->id;
                        $user->save();
        
                        $accountLink = \Stripe\AccountLink::create([
                            'account' => $account->id,
                            'refresh_url' => route('stripe.onboard-result', ['token' => Crypt::encrypt($user->stripe_connect_id)]),
                            'return_url' => route('stripe.onboard-result', ['token' => Crypt::encrypt($user->stripe_connect_id)]),
                            'type' => 'account_onboarding',
                        ]);
        
                        return response()->json([
                            'message' => 'User updated successfully, please complete Stripe onboarding.',
                            'onboarding_url' => $accountLink->url,
                            'user' => $user,
                        ]);
                    } catch (\Stripe\Exception\ApiErrorException $e) {
                        Log::error('Stripe API error:', [
                            'message' => $e->getMessage(),
                            'code' => $e->getStripeCode(),
                            'httpStatus' => $e->getHttpStatus(),
                            'request' => $e->getRequestId(),
                            'error' => $e->getError()
                        ]);
                        return response()->json(['message' => 'Error creating Stripe account: ' . $e->getMessage()], 500);
                    } catch (\Exception $e) {
                        Log::error('General error:', ['message' => $e->getMessage()]);
                        return response()->json(['message' => 'Error creating Stripe account: ' . $e->getMessage()], 500);
                    }
                }
        
                return response()->json([
                    'message' => 'User updated successfully',
                    'user' => $user,
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Validation error:', ['errors' => $e->errors()]);
                return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
            } catch (\Exception $e) {
                Log::error('General error:', ['message' => $e->getMessage()]);
                return response()->json(['message' => 'Error updating user profile: ' . $e->getMessage()], 500);
            }
        }
        
        public function onboardSeller()
        {
            $user = Auth::user();
            
            // Ensure Stripe API key is set
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        
            // Create the Stripe account if it doesn't exist
            if (empty($user->stripe_connect_id)) {
                $account = \Stripe\Account::create([
                    'type'         => 'express',
                    'email'        => $user->email,
                    'country'      => 'US',
                    'capabilities' => [
                        'card_payments' => ['requested' => true],
                        'transfers'     => ['requested' => true],
                    ],
                    'settings'     => [
                        'payouts' => [
                            'schedule' => [
                                'interval' => 'manual',
                            ],
                        ],
                    ],
                ]);
        
                $user->stripe_connect_id = $account->id;
                $user->save();
            }
        
            // Generate the onboarding link
            $user->refresh();
            $onBoardLink = \Stripe\AccountLink::create([
                'account'     => $user->stripe_connect_id,
                'refresh_url' => route('stripe.onboard-result', Crypt::encrypt($user->stripe_connect_id)),
                'return_url'  => route('stripe.onboard-result', Crypt::encrypt($user->stripe_connect_id)),
                'type'        => 'account_onboarding',
            ]);
        
            return response()->json(['url' => $onBoardLink->url]);
        }

        public function onboardResult($encodedToken)
        {
            try {
                $decodedToken = Crypt::decrypt($encodedToken);
                $user = User::where('stripe_connect_id', $decodedToken)->firstOrFail();
                $user->stripe_on_boarding_completed_at = Carbon::now();
                $user->save();
        
                return redirect('/dashboard');
            } catch (\Exception $e) {
                Log::error('Decryption error:', ['message' => $e->getMessage()]);
                return response()->json(['message' => 'Error processing the request: ' . $e->getMessage()], 500);
            }
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
                'enterprise_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:20480', // 20 MB
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
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:20480', // 20 MB
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
