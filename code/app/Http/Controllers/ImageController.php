<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\User;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    static $default = 'default.jpg';
    static $diskName = 'Tutorial02';

    static $systemTypes = [
        'profile' => ['png', 'jpg', 'gif', 'svg', 'jpeg'],
    ];

    private static function isValidType(String $type) {
        return array_key_exists($type, self::$systemTypes);
    }
    
    private static function defaultAsset(String $type) {
        return asset($type . '/' . self::$default);
    }
    
    private static function getFileName (int $id) {
            
        $fileName = null;
        
        $user = User::find($id);
        if ($user) {
            $image_id = $user->image_id;
            if ($image_id) {
                $image = Image::find($image_id);
                if ($image) {
                    $fileName = $image->filename;
                }
            }
        }
    
        return $fileName;
    }
    
    static function get(String $type, int $userId) {
    
        // Validation: upload type
        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }
    
        // Validation: file exists
        $fileName = self::getFileName($userId);
        if ($fileName) {
            $filePath = $type . '/' . $fileName;
            if (Storage::disk(self::$diskName)->exists($filePath)) {
                return asset($filePath);
            }
        }
    
        // Not found: returns default asset
        return self::defaultAsset($type);
    }

    public function getAJAX(Request $request) {
        $type = $request->type;
        $userId = $request->userId;
        // Validation: upload type
        if (!self::isValidType($type)) {
            return response()->json(self::defaultAsset($type), 200);
        }
    
        // Validation: file exists
        $fileName = self::getFileName($userId);
        if ($fileName) {
            $filePath = $type . '/' . $fileName;
            if (Storage::disk(self::$diskName)->exists($filePath)) {
                \Log::info('asset: ' . $filePath);
                return response()->json($filePath, 200);
            }
        }
    
        // Not found: returns default asset
        return response()->json(self::defaultAsset($type), 200);
    }

    public static function create($actualImage, string $filename){

        // Check if the filename is not empty
        if (empty($filename)) {
            return;
        }

        // Store the image in the database
        $image = new Image;

        $image->filename = $filename;

        $image->save();

        $actualImage->storeAs('post', $filename, self::$diskName);

        $image = Image::where('filename', $filename)->first();

        if ($image) {
            return $image->id;
        }
        
        // Return a response
        return;
    }

    public function createUserImage(Request $request, int $userId)
    {
        // Validate input
        $request->validate([
            'image' => 'required|image|max:2048|dimensions:max_width=320, max_height=320|mimes:jpg,jpeg,svg,gif,png',
        ]);

        // Retrieve the filename from the request
        $filename = $request->file('image')->getClientOriginalName();

        // Check if the filename is not empty
        if (empty($filename)) {
            return response()->json(['message' => 'Filename is empty'], 400);
        }

        $hashedFilename = hash('sha256', $filename . time());

        // Store the image in the database
        $image = new Image;

        $image->filename = $hashedFilename;

        $image->save();

        $request->image->storeAs('profile', $hashedFilename, self::$diskName);

        if ($image->id) {
            UserController::updateImage($userId, $image->id);

            // Return a response
            return redirect()->route('profile_page', ['id' => $userId])->with('success', 'Image changed successfully.');

        }
        
        // Return a response
        return redirect()->route('profile_page', ['id' => $userId])->with('error', 'Image could not be changed.');
    }
}
