<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Comment extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $table = "comment";

    protected $fillable = [
        'title',
        'caption',
        'commentdate',
        'upvotes',
        'downvotes',
        'post_id',
        'user_id',
    ];

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin that owns the comment.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }


    /**
     * Get the post that owns the comment.
     */

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function upvotes()
    {
        return $this->hasMany(UpvoteComment::class);
    }

    public function downvotes()
    {
        return $this->hasMany(DownvoteComment::class);
    }

    /**
    * Get the image for the comment.
    */
    public function image(): HasOneThrough
    {
        return $this->hasOneThrough(
            Image::class,
            ImageComment::class,
            'comment_id', // Foreign key on image_comment table...
            'id', // Foreign key on images table...
            'id', // Local key on comments table...
            'image_id' // Local key on image_comment table...
        );
    }

    public function checkIfUserUpvoted()
    {
        $userId = Auth::id();
        return $this->upvotes()->where('user_id', $userId)->exists();
    }

    public function checkIfUserDownvoted()
    {
        $userId = Auth::id();
        return $this->downvotes()->where('user_id', $userId)->exists();
    }
}
?>