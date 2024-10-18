<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Post;
use App\Events\NewNotification;

class DownvotedPost extends Notification
{

    
    protected $downvoter;
    protected $post;

    public function __construct(User $downvoter, Post $post)
    {
        $this->downvoter = $downvoter;
        $this->post = $post;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        event(new NewNotification($notifiable));
        return [
            'sender_id' => $this->downvoter->id,
            'name' => $this->downvoter->name,
            'title' => $this->post->title,
            'description' => "Downvoted Post",
        ];
    }
}
?>