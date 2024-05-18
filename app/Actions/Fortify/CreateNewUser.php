<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input)
    {
        if (isset($input['is_seller']) && $input['is_seller'] === 1) {
            return $this->createSeller($input);
        }

        return $this->createUser($input);
    }
    
    public function createUser(array $input): User
    {
        Validator::make($input, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }

    public function createSeller(array $input): User
    {
        Validator::make($input, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'street' => ['string', 'min:0', 'max:255'],
            'house_number' => ['string', 'max:255'],
            'zip_code' => ['string', 'max:255'],
            'city' => ['string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'is_seller' => [],
            'password' => $this->passwordRules(),
        ])->validate();


        return User::create([
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'street' => $input['street'],
            'house_number' => $input['house_number'],
            'zip_code' => $input['zip_code'],
            'city' => $input['city'],
            'email' => $input['email'],
            'is_seller' => $input['is_seller'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
