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

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'sender_id');
    }
}
