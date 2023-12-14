<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function editProduct(User $user, Product $product)
    {
        // Check if the user is the owner of the product or is an admin
        return $user->isAdmin() || $user->id === $product->vendor_id;
    }

    public function deleteProduct(User $user, Product $product)
    {
        // Check if the user is the owner of the product or is an admin
        return $user->isAdmin() || $user->id === $product->vendor_id;
    }
}
