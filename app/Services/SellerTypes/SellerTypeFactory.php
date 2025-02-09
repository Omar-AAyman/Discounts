<?php

namespace App\Services\SellerTypes;

use InvalidArgumentException;

class SellerTypeFactory
{
    public static function getStrategy($sellerTypeId)
    {
        return match ($sellerTypeId) {
            1 => new SellerType1Strategy(),
            2 => new SellerType2Strategy(),
            3 => new SellerType3Strategy(),
            4 => new SellerType4Strategy(),
            default => throw new InvalidArgumentException('Invalid seller type'),
        };
    }
}
