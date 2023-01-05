<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'account_from_id', 'account_to_id', 'amount', 'currency', 'type'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAccountFrom(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_from_id');
    }

    public function getAccountTo(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_to_id');
    }
}
