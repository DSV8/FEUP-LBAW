<?php

namespace App\Policies;

use App\Models\Image;
use App\Models\User;

class ImagePolicy
{
    public function get(User $user): bool
    {
        return Auth::user()->id === $user->id || $user->isAdmin;
    }

    public function createUserImage(User $user): bool
    {
        return Auth::user()->id === $user->id || $user->isAdmin;
    }
}
