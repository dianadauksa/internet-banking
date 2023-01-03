<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'name', 'account_number', 'currency', 'balance', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
