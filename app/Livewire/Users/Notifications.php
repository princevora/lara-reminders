<?php

namespace App\Livewire\Users;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class Notifications extends Component
{
    /**
     * @var mixed
     */
    public $user_id;

    /**
     * @var Collection $notifications
     */
    public Collection $notifications;

    /**
     * @var int $unreadNotifications
     */
    public int $unreadNotifications;

    /**
     * @var $user
     */
    public $user;

    public function mount()
    {
        $this->user_id = auth()->user()->id;
        $this->user = auth()->guard('web')->user();

        // Initialize the notifications and the unread notifications
        $this->updateNotifications();
    }

    private function updateNotifications()
    {
        $this->notifications = $this->getNotifications();
        $this->unreadNotifications = $this->notifications->whereNull('read_at')->count();
    }
    
    #[On('echo-private:send-notification.{user_id},SendNotificationEvent')]
    public function listenForMessage($data)
    {
        $this->notifications = $this->getNotifications();
        $this->unreadNotifications = $this->notifications->whereNull('read_at')->count();
    }

    /**
     * @return Collection<int, Notification>
     */
    private function getNotifications(): Collection
    {
        return Notification::where('user_id', $this->user->id)->get();
    }

    #[On('notifications:update')]
    public function listenForUpdatedNotifications()
    {
        $this->updateNotifications();
    }
}
