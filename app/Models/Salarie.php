<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salarie extends Model
{
    /** @use HasFactory<\Database\Factories\SalarieFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
