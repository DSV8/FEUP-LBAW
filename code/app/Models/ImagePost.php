<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagePost extends Model
{
    use HasFactory;

    public $timestamps  = false;

    protected $table = 'image_post';

    protected $primaryKey = 'image_id';

    protected $fillable = [
        'image_id',
        'post_id',
    ];

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function image() {
        return $this->belongsTo(Image::class);
    }

    public static function getImageId($filename) {
        return Image::getImageId($filename);
    }
}
