<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Topic extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = "topic";

    // Define the columns that can be filled
    protected $fillable = [
        'title',
        'caption',
        'followers',
    ];

    // Disable timestamps
    public $timestamps = false;

    /**
     * Get the topic associated with the post.
     */
    public function post()
    {
        return $this->hasMany(Post::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'topic_follow', 'followed_tag_id', 'follower_id');
    }
    
}

?>