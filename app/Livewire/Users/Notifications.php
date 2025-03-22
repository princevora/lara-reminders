<?php

namespace App\Livewire\Users;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class Notifications extends Component
{
    /**
     * @var string
     */
    public string $user_id;

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
    public function getNotifications(): Collection
    {
        return Notification::where('user_id', $this->user->id)->get();
    }
}
