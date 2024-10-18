<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public $timestamps  = false;

    protected $table = 'image';

    protected $fillable = [
        'filename',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function imagepost() {
        return $this->HasMany(ImagePost::class);
    }

    public static function getImageId($filename) {
        return self::where('filename', $filename)->value('id');
    }
}