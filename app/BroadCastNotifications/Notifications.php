<?php

namespace App\BroadCastNotifications;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Builder;

class Notifications
{
    /**
     * @var $user_id
     */
    public $user_id;
    
    /**
     * @param mixed $user_id
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @param mixed $notification_id
     * @return bool
     */
    public function markAsRead($notification_id): bool
    {
        if(is_null($notification_id))
        {
            return false;
        }

        // find the notification
        return Notification::where('id', $notification_id)
                ->update([
                    'read_at' => now()
                ]);
    }

    public function markAllAsRead()
    {
        // get all the notifications from the users
        return Notification::where(function($query){
                $query->where('user_id', $this->user_id)
                    ->whereNull('read_at');
            })
            ->each(function ($notification){
                $notification->update([
                    'read_at' => now()
                ]);
            });
    }
}
