<?php

namespace App\Http\Controllers;

use App\Models\TopicProposal;
use App\Models\Topic;

use Illuminate\Http\Request;

class TopicProposalController extends Controller
{
    public function create(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'topic_name' => 'required|string|max:255',
            'caption' => 'required|string|max:1000',
        ]);

        // Create the topic proposal
        $topicProposal = new TopicProposal();
        $topicProposal->title = $validatedData['topic_name'];
        $topicProposal->caption = $validatedData['caption'] ;
        $topicProposal->user_id = auth()->id(); // Assuming the user is authenticated
        $topicProposal->save();

        return redirect()->route('followed_topics'); 
    }

    public function listProposals()
    {
        $proposals = TopicProposal::orderBy('title', 'asc')->get();

        return view('pages.admin_topic_proposals', ['proposals' => $proposals]);
    }

    public function createTopic(TopicProposal $proposal)
    {
        Topic::create([
            'title' => $proposal->title,
            'caption' => $proposal->caption,
            'followers' => 0,
        ]);

        // Delete the proposal
        $proposal->delete();

        // Redirect back or to a specific route
        return redirect()->route('admin_topic_proposals')->with('success', 'Topic created successfully!');
    }

    public function deleteProposal(TopicProposal $proposal)
    {
        $proposal->delete();

        // Redirect back or to a specific route
        return redirect()->route('admin_topic_proposals')->with('success', 'Proposal deleted successfully!');
    }
}
