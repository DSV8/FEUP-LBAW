<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImagePost;
use App\Models\Image;
use App\Http\Controllers\ImageController;

class ImagePostController extends Controller
{
    static $diskName = 'Tutorial02';

    static $systemTypes = [
        'post' => ['png', 'jpg', 'gif', 'svg', 'jpeg'],
    ];

    public static function create(Request $request, int $post_id)
    {
        $errors = [];

        foreach ($request->images as $image) {
            // Retrieve the filename from the request
            $filename = $image->getClientOriginalName();

            // Check if the filename is not empty
            if (empty($filename)) {
                array_push($errors, 'Filename is empty');
                continue;
            }

            $hashedFilename = hash('sha256', $filename . time());

            $imageId = ImageController::create($image, $hashedFilename);

            // Verify if imageId is empty
            if (empty($imageId)) {
                array_push($errors, 'Could not create '.$filename);
                continue;
            }

            // Store the image in the database
            $image_post = new ImagePost;

            $image_post->image_id = $imageId;
            $image_post->post_id = $post_id;

            $image_post->save();

            $image = Image::where('filename', $hashedFilename)->first();

            if (!$image) {
                array_push($errors, 'Could not create '.$filename);
            }
        }

        if(empty($errors)){
            return true;
        } else {
            return $errors;
        }    
    }
}
