<?php

namespace App\Livewire\Users;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationList extends Component
{
    /**
     * @var Collection $notifications
     */
    public Collection $notifications;

    /**
     * @var array<string, string>
     */
    public array $notifications_types = [
        'event_reminder' => 'calendar-days',
        'new_venue_request' => 'plus',
        'system_notifications' => 'computer-desktop'
    ];

    /**
     * @var $user
     */
    public $user;

    /**
     * @var $user_id
     */
    public $user_id;

    /**
     * @return void
     */
    public function mount()
    {
        $this->user = auth()->guard('web')->user();
        $this->user_id = $this->user->id;
        $this->notifications = $this->getNotifications();
    }

    /**
     * @param mixed $data
     * @return void
     */
    #[On('echo-private:send-notification.{user_id},SendNotificationEvent')]
    public function listenForMessage($data)
    {
        $this->notifications = $this->getNotifications();
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications()
    {
        return Notification::where('user_id', $this->user_id)->latest()->get();
    }
}
