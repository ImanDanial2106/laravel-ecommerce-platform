<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
        'total_price',
        'order_number', // <-- THIS LINE IS CRITICAL
    ];

    /**
     * Get the customer that this order belongs to.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product that this order belongs to.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}