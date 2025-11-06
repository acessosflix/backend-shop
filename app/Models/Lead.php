<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'source',
        'status',
        'value_estimate',
        'notes',
    ];

    protected $casts = [
        'status' => LeadStatus::class,
        'value_estimate' => 'decimal:2',
    ];
}
