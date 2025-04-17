<?php

// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name', 'phone', 'status', 'coupon_code', 'discount', 'total', 'image_url', 'user_id'
    ];

    // Default values for newly created orders
    protected $attributes = [
        'status' => 'Pending',  // Default status for new orders
    ];

    // Remove the relationships for product and items
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
