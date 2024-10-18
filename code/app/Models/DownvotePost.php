<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownvotePost extends Model
{
    use HasFactory;

    protected $table = 'downvote_post'; // Specify the table name
    protected $fillable = ['post_id', 'user_id']; // Specify the fillable columns

    public $timestamps = false;

    // Relationships if needed
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}