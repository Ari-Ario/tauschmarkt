<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['total_price', 'status', 'session_id',  'created_by', 'updated_by'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    function order_items()  {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
