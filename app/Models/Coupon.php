<?php

// app/Models/Coupon.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'min_order_amount', 'usage_limit', 'used_count', 'expires_at'
    ];

    public function isValid()
    {
        return (!$this->expires_at || $this->expires_at >= now())
            && (!$this->usage_limit || $this->used_count < $this->usage_limit);
    }
}

