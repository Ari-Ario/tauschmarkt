<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $dateFormat = 'd. M Y';
            return [
                'id' => $this->id,
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'is_seller' => $this->is_seller,
                'profile_picture' => $this->profile_picture
                    ? asset($this->profile_picture)
                    : asset('storage/profile_images/default.png'),
    
                'enterprise_picture' => $this->enterprise_picture
                    ? asset('storage/enterprise_images/' . $this->enterprise_images)
                    : asset('storage/enterprise_images/default.png'),
                'street' => $this->street,
                'house_number' => $this->house_number,
                'city' => $this->city,
                'zip_code' => $this->zip_code,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
    
                'created_at' => optional($this->created_at)->format($dateFormat),
                'updated_at' => optional($this->updated_at)->format($dateFormat),
            ];

        
    }
}
