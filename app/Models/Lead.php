<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    /** @use HasFactory<\Database\Factories\V1\LeadFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
