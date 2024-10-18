<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether a user can see a post.
     */
    public function show(User $user): bool
    {
        // Every user can see all posts
        return true;
    }

    /**
     * Determine whether the user can list posts.
     */
    public function list(User $user): bool
    {
        // Every user can see all posts
        return true;
    }

    /**
     * Determine whether the user can create posts.
     */
    public function create(User $user): bool
    {
        // Only authenticated users can create posts
        return auth()->check();
    }
        
    /**
     * Determine whether the user can update the post.
     */
    public function update(User $user, Post $post): bool
    {
        // Only the user who created the post can update it
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can delete the post.
     */
    public function delete(User $user, Post $post): bool
    {
        // Only the user who created the post or an admin can delete it
        return $user->id === $post->user_id || $user->isAdmin();
        
    }
}
