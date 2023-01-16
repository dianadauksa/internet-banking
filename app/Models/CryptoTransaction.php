<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'coin',
        'amount',
        'price',
        'total',
        'account_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
