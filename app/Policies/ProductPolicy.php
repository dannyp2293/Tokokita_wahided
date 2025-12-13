<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }


    public function view(User $user, Product $product): bool
    {
        return false;
    }


   public function create(User $user)
{
    return $user->role === 'superadmin';
}

public function update(User $user, Product $product)
{
    return $user->role === 'superadmin';
}

public function delete(User $user, Product $product)
{
    return $user->role === 'superadmin';
}

}
