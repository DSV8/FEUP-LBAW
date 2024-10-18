<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;

class TopicController extends Controller
{
    public function showCreatePostForm(){
        $topics = Topic::all(); // Retrieve all topics
        return view('pages.create_post', ['topics' => $topics]);

    }
    public function showFiltersTopic(){
        $topics = Topic::all(); // Retrieve all topics
        return response()->json(['topics' => $topics]);
    }
    
    public function toggleFollow(Request $request, $topicId)
    {
        $user = $request->user(); // Retrieve the authenticated user
        $topic = Topic::findOrFail($topicId); // Find the topic by ID

        if ($user->followedTopics()->where('id', $topicId)->exists()) {
            $user->followedTopics()->detach($topicId); // Unfollow the topic
            return response()->json(['message' => 'Unfollowed', 'topicTitle' => $topic->title]);
        } else {
            $user->followedTopics()->attach($topicId); // Follow the topic
            return response()->json(['message' => 'Followed', 'topicTitle' => $topic->title]);
        }
    }
}
?>