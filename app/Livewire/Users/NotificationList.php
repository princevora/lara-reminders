<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;

class NotificationList extends Component
{
    public $notifications = [];

    public function mount()
    {
        // Static notifications
        $this->notifications = [
            [
                'icon' => 'bell',
                'title' => 'System Update',
                'message' => 'A system update is scheduled for this weekend.',
                'link' => '#',
                'time' => 'Just now',
            ],
            [
                'icon' => 'alert-triangle',
                'title' => 'Security Alert',
                'message' => 'Unusual login attempt detected from a new device.',
                'link' => '#',
                'time' => '10 min ago',
            ],
            [
                'icon' => 'cloud',
                'title' => 'Server Maintenance',
                'message' => 'Servers will be down for maintenance from 2 AM - 5 AM UTC.',
                'link' => '#',
                'time' => '1 hour ago',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.users.notification-list');
    }
}
