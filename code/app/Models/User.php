<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Http\Controllers\ImageController;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'country',
        'birthday',
        'gender',
        'url',
        'image_id',
        'blocked',
        'password',
        'google_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the posts for the user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the comments for the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Gets the admin associated with the user (if he his one).
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Get the image associated with the user.
     */
    public function image(): HasOne
    {
        return $this->hasOne(Image::class);
    }

    /**
    * Check if the user is an admin.
    *
    * @return bool
    */
    public function isAdmin(): bool
    {
        return Admin::where('user_id', $this->id)->exists();
    }

    public function getProfileImage() {
        return ImageController::get('profile', $this->id);
    }

    public function followersUsers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'following_id', 'follower_id');
    }

    public function followingUsers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follower_id', 'following_id');
    }
    
    public function followedTopics()
    {
        return $this->belongsToMany(Topic::class, 'user_topic', 'user_id', 'topic_id');
    }

    /**
    * Get the password recovery entries for the user.
    */
    public function passwordRecoveryEntries(): HasMany
    {
        return $this->hasMany(PasswordRecovery::class);
    }
}
?>