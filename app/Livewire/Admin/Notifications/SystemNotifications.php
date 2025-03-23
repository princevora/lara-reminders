<?php

namespace App\Livewire\Admin\Notifications;

use App\BroadCastNotifications\SendNotification;
use App\Mail\SystemNotificationMail;
use App\Models\User;
use Livewire\Component;

class SystemNotifications extends Component
{
    /**
     * @var string $message
     */
    public string $message = "Update! Our System Has Just Brought A New Feature";

    /**
     * @var array<string, bool>
     */
    public $notification_channels = [
        'web_sockets' => true,
        'email' => false,
        // 'push' => false
    ];

    public function notifyUsers()
    {
        if (empty(array_filter($this->notification_channels))) {
            return $this->dispatch('issues:show', 'Select Atleat One Channel')->to(NotificationTypes::class);
        }

        $this->validate([
            'message' => 'required'
        ]);

        $users = User::all();

        if ($users->count() > 0) {
            if ($this->notification_channels['web_sockets']) {
                $this->websocketChannel($users);
            }

            if ($this->notification_channels['email']) {
                $this->emailChannel($users);
            }

            return $this->dispatch('success:show', 'Notifications Has been sent')->to(NotificationTypes::class);
        }
    }

    /**
     * @param mixed $users
     * @return void
     */
    private function websocketChannel($users)
    {
        foreach ($users as $user) {
            // BroadCast the message
            (new SendNotification($user, $this->message, $type = "system_notifications"))->notify();
        }
    }

    private function emailChannel($users)
    {
        foreach ($users as $user) {
            // BroadCast the message
            (new SendNotification($user, $this->message))->notifyEmailChannel(SystemNotificationMail::class, [
                $user,
                $this->message
            ]);
        }
    }
}
