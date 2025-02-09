<?php
namespace App\Repositories;

use App\Models\Seller;
use App\Models\User;

class SellerRepository
{
    public function store(array $data): User
    {
        return User::create($data);
    }
}
