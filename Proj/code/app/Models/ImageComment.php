<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageComment extends Model
{
    use HasFactory;

    public $timestamps  = false;

    protected $table = 'image_comment';

    protected $primaryKey = 'image_id';

    protected $fillable = [
        'image_id',
        'comment_id',
    ];

    public function comment() {
        return $this->belongsTo(Comment::class);
    }

    public function image() {
        return $this->belongsTo(Image::class);
    }

    public static function getImageId($filename) {
        return Image::getImageId($filename);
    }
}
