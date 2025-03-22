<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Component;

class NotificationTypes extends Component
{

    /**
     * @var $selected int
     */
    public $selected = 1;

    /**
     * @var array<int, string>
     */
    public $components = [
        1 => EventReminders::class, //Event reminder notification
        2 => NewBookingRequest::class,
        3 => SystemNotifications::class
    ];
}
