<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    use HasFactory;

    protected $fillable = [
        // other fields
        'first_user_id',
        'user_id',
        'is_favorite',
    ];

    public function favorites()
    {
        return $this->hasMany(User::class, 'user_id', 'first_user_id', 'is_favorite');
    }

}
