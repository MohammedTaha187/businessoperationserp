<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /** @use HasFactory<\Database\Factories\V1\NotificationFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
