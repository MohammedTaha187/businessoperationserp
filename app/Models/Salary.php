<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    /** @use HasFactory<\Database\Factories\SalaryFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function booted()
    {
        static::creating(function ($salary) {
            $salary->net = $salary->basic + $salary->bonuses - $salary->deductions;
        });

        static::updating(function ($salary) {
            $salary->net = $salary->basic + $salary->bonuses - $salary->deductions;
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
