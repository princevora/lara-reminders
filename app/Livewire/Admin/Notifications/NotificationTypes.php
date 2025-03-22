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
    public $selected = 1;

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
    public function placeholder()
    {
        return <<<HTML
            <div class="flex items-center justify-center h-screen">
                <div class="m-auto max-w-full max-lg:min-w-fit lg:max-w-96 flex justify-center">
                    <div>
                        <div class="flex items-end gap-4">
                            <svg class="shrink-0 [:where(&amp;)]:size-6 animate-spin" data-flux-icon="" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
    }

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
