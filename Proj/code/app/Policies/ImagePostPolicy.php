<?php

namespace App\Policies;

use App\Models\User;

class ImagePostPolicy
{
    public function create(User $user): bool
    {
        return Auth::user()->id === $user->id;
    }
}
