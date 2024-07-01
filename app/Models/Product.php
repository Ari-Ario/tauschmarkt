<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'category_id',
        'name',
        'description',
        'amount',
        'price',
        'mass_unit'
        // 'published',
        // 'product_picture',
        // 'deleted_by',
    ];

    public function product_images()
    {
        return $this->hasMany(ProductImages::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }


    //filter logic for price or categories or brands 

    public function  scopeFiltered(Builder $quary)  {
        $quary
        ->when(request('categories'), function (Builder $q)  {
            $q->whereIn('category_id',request('categories'));
        })
        ->when(request('prices'), function(Builder $q)  {
            $q->whereBetween('price',[
                request('prices.from',0),
                request('prices.to', 100000),
            ]);
        });
        
    }
}
