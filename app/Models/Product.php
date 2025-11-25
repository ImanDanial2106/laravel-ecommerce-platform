<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Make sure this line is here

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
    ];

    /**
     * Get the orders for the product.
     *
     * This function is required to delete a product
     * that might have orders attached to it.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}