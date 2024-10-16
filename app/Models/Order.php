<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'checkout_link',
        'external_id',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
