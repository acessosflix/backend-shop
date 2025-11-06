<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserClient extends Model
{
    use HasFactory;

    protected $table = 'user_clients';

    protected $fillable = [
        'user_id',
        'address',
        'city',
        'state',
        'zipcode',
        'avatar',
    ];

    /**
     * Relação com User (dados básicos)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relação com Orders
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
