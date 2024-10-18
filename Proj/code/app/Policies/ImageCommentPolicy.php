<?php

namespace App\Policies;

use App\Models\User;

class ImageCommentPolicy
{
    public function create(User $user): bool
    {
        return Auth::user()->id === $user->id;
    }
}
