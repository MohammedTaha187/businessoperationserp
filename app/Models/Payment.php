<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the parent payable model (Invoice, Order).
     */
    public function payable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that initiated the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
