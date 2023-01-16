<?php

namespace App\Models;

use Illuminate\Support\Collection;

class CryptoCoinCollection extends Collection
{
    public function __construct($items = [])
    {
        parent::__construct($items);
    }
}

