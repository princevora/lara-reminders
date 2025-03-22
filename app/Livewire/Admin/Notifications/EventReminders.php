<?php

namespace App\Livewire\Admin\Notifications;

use App\Events\SendNotificationEvent;
use App\Models\Event;
use App\Models\User;
use App\Notifications\EventReminderNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Illuminate\Support\Collection;
use Livewire\WithPagination;

class EventReminders extends Component
{
    use WithPagination; 

    /**
     * @var string
     */
    public string $message;

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.admin.notifications.event-reminders', [
            'events' => Event::paginate(10) 
        ]);
    }

    /**
     * @param string $id
     * @return void
     */
    public function notifyUser(string $id)
    {
        $this->validate([
            'message' => 'required|string'
        ]);
        
        $user = User::find($id);
        broadcast(new SendNotificationEvent($this->message, $id))->toOthers();
    }
}
