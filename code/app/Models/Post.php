<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = "post";

    // Define the columns that can be filled
    protected $fillable = [
        'title',
        'caption',
        'postdate',
        'upvotes',
        'downvotes',
        'user_id',
        'topic_id',
        'video_id'
    ];

    // Disable timestamps
    public $timestamps = false;

    /**
     * Get the user that created the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the topic associated with the post.
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the video associated with the post.
     */
    public function video()
    {
        return $this->HasOne(Video::class);
    }

    /**
     * Get the comments for the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the upvotes for the post.
     */
    public function upvotes()
    {
        return $this->hasMany(UpvotePost::class);
    }
    public function downvotes()
    {
        return $this->hasMany(DownvotePost::class);
    }

    public function images()
    {
        return $this->belongsToMany(
            Image::class,
            'image_post', // Pivot table name...
            'post_id', // Foreign key on the pivot table related to the Post model...
            'image_id' // Foreign key on the pivot table related to the Image model...
        )->pluck('filename')->toArray();
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