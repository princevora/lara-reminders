<?php

namespace App\Livewire\Users;

use App\BroadCastNotifications\Notifications;
use App\Models\Notification;
use App\Livewire\Users\Notifications as NotificationsComponent;
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
     * @var $user
     */
    public $user;

    /**
     * @var $user_id
     */
    public $user_id;

    /**
     * @var int $unreadNotifications
     */
    public int $unreadNotifications;

    /**
     * @var array<string, string>
     */
    public array $notifications_types = [
        'event_reminder' => 'calendar-days',
        'new_venue_request' => 'plus',
        'system_notifications' => 'computer-desktop'
    ];

    /**
     * @return void
     */
    public function mount()
    {
        $this->user = auth()->guard('web')->user();
        $this->user_id = $this->user->id;
        $this->notifications = $this->getNotifications();
        $this->unreadNotifications = $this->notifications
            ->whereNull('read_at')
            ->count();
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

    public function markAsRead($notification_id)
    {
    }

    public function markAllAsRead()
    {
        // Mark notifications as read and update them
        if((new Notifications($this->user_id))->markAllAsRead()){
            $this->notifications = $this->getNotifications();

            // dispatch the event and update notifications for sidebar.
            $this->dispatch('notifications:update')->to(NotificationsComponent::class);
        }
    }
}
