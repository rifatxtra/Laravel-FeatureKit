<?php

namespace App\Listeners;

use App\Events\NotificationCreated;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateNotificationRecord implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a notification record when a notification event is dispatched.
     */
    public function handle(NotificationCreated $event): void
    {
        Notification::create([
            'user_id'     => $event->user->id,
            'category'    => $event->category,
            'title'       => $event->title,
            'description' => $event->message,
        ]);
    }
}
