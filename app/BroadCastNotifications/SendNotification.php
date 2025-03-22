<?php

namespace App\BroadCastNotifications;

use App\Events\SendNotificationEvent;
use App\Models\Notification;
use App\Models\User;

class SendNotification
{
    /**
     * @var $users 
     */
    public $users;

    /**
     * @var string $message
     */
    public string $message = '';

    /**
     * @param mixed $users
     */
    public function __construct($users = [], string $message)
    {
        $this->users = $users;
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function notify(): bool
    {
        if(empty($this->users) || is_null($this->users)){
            return false;
        }

        foreach ($this->users as $user) {
            // Store the notifications
            Notification::create([
                'user_id' => $user->id,
                'message' => $this->message,
                'read_at' => null
            ]);

            broadcast(new SendNotificationEvent($this->message, $user->id))->toOthers();
        }
        
        return true;
    }
}
