<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_percentage',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Verifica se o cupom está válido
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->valid_until && $this->valid_until->isPast()) {
            return false;
        }

        return true;
    }
}
