<?php

namespace App\Livewire\Admin\Notifications;

use App\BroadCastNotifications\SendNotification;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class EventReminders extends Component
{
    use WithPagination;

    /**
     * @var string
     */
    public string $message = "Event Reminder! The upcoming {event} event on {date} dont't forget to join it.. ";

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
     * 
     * @param string $id
     * @return \Livewire\Features\SupportEvents\Event|void
     */
    public function notifyUser(string $id)
    {
        $this->validate([
            'message' => 'required|string'
        ]);

        $user = User::find($id);
        
        if (!$user) {
            return $this->dispatch('issues:show', 'User not found')->to(NotificationTypes::class);
        }

        // Get the list of events which the user is going to attend.
        $events = Event::where('user_id', $id)->get();

        if ($events->count() > 0) {
            foreach ($events as $event) {
                // Generate a random date
                $randomDate = Carbon::now()->addDays(rand(1, 365))->format('D-m-Y');

                // Replace variables
                $message = str_replace(['{event}', '{date}'], [$event->title, $randomDate], $this->message);
                (new SendNotification($user, $message, $type = "event_reminder"))->notify();
            }

            return $this->dispatch('success:show', 'Notifications Has been sent')->to(NotificationTypes::class);
        }
    }

    /**
     * @return \Livewire\Features\SupportEvents\Event|null
     */
    public function notifyAll()
    {
        // get the events
        $events = Event::all();

        if($events->count() > 0){
            // Send each event's reminder
            foreach ($events as $event) {
                // Generate a random date
                $randomDate = Carbon::now()->addDays(rand(1, 365))->format('D-m-Y');
    
                // Replace variables
                $message = str_replace(['{event}', '{date}'], [$event->title, $randomDate], $this->message);
                
                // BroadCast the message
                (new SendNotification($event->user, $message, $type = "event_reminder"))->notify();
            }
    
            return $this->dispatch('success:show', 'Notifications Has been sent')->to(NotificationTypes::class);
        }
    }
}
