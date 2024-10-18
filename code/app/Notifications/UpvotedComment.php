<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Comment;
use App\Events\NewNotification;

class UpvotedComment extends Notification
{

    
    protected $upvoter;
    protected $comment;

    public function __construct(User $upvoter, Comment $comment)
    {
        $this->upvoter = $upvoter;
        $this->comment = $comment;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        event(new NewNotification($notifiable));
        return [
            'sender_id' => $this->upvoter->id,
            'name' => $this->upvoter->name,
            'title' => $this->comment->title,
            'description' => "Upvoted Comment",
        ];
    }
}
?>