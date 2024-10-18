<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageComment;
use App\Models\Image;

class ImageCommentController extends Controller
{
    static $diskName = 'Tutorial02';

    static $systemTypes = [
        'post' => ['png', 'jpg', 'gif', 'svg', 'jpeg'],
    ];

    public static function create(Request $request, int $comment_id){

            $filename = $request->image->getClientOriginalName();

            // Check if the filename is not empty
            if (empty($filename)) {
                return response()->json(400);
            }

            $hashedFilename = hash('sha256', $filename . time());

            $imageId = ImageController::create($request->image, $hashedFilename);
            echo $imageId;
            
            // Verify if imageId is empty
            if (empty($imageId)) {
                return response()->json(400);
            }

            // Store the image in the database
            $image_comment = new ImageComment;

            $image_comment->image_id = $imageId;
            $image_comment->comment_id = $comment_id;

            $image_comment->save();

            $image = Image::where('filename', $hashedFilename)->first();

            if (!$image) {
                return response()->json(400);
            }

            // Pass the code and message to the other side
            return response()->json(200);
    }

}
