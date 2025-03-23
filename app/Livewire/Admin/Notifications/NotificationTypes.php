<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\Attributes\On;

class NotificationTypes extends Component
{

    /**
     * @var int $selected
     */
    public $selected = 3;

    /**
     * @var string $message
     */
    public string $message;

    /**
     * 0 = issue - error message
     * 1 = success message
     * 
     * @var null|int $messageType
     */
    public ?int $messageType = null;

    /**
     * @var array<int, string>
     */
    public $components = [
        1 => EventReminders::class, //Event reminder notification
        2 => NewBookingRequest::class,
        3 => SystemNotifications::class
    ];

    /**
     * $issue is treated as a message 
     * if any error occurs from the child components of this instance
     * we can show the issue (message) 
     * 
     * @param mixed $issue
     * @return void
     */
    #[On('issues:show')]
    public function showIssue($issue)
    {
        $this->message = $issue;
        $this->messageType = 0;

        // Use Livewire's Js helper to execute JavaScript after 2 seconds
        $this->dispatch('clearMessage');
    }

    /**
     * @param mixed $message
     * @return void
     */
    #[On('success:show')]
    public function showSuccess($message)
    {
        $this->message = $message;
        $this->messageType = 1;

        $this->dispatch('clearMessage');
    }
}
