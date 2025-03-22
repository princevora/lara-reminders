<?php

namespace App\Livewire\Admin\Notifications;

use App\BroadCastNotifications\SendNotification;
use App\Mail\EventReminderMail;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithPagination;

class EventReminders extends Component
{
    use WithPagination;

    /**
     * @var string
     */
    public string $message = "Event Reminder! The upcoming {event} event on {date} dont't forget to join it.. ";

    public $notification_channels = [
        'web_sockets' => true,
        'email' => false,
        // 'push' => false
    ];

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
    public function notifyUser(string $id, $event_id)
    {
        if (empty(array_filter($this->notification_channels))) {
            return $this->dispatch('issues:show', 'Select Atleat One Channel')->to(NotificationTypes::class);
        }

        $event = Event::where('id', $event_id)->where('user_id', $id)->first();
        $user = User::find($id);

        if (!$user) {
            return $this->dispatch('issues:show', 'User not found')->to(NotificationTypes::class);
        }

        if ($this->notification_channels['web_sockets']) {
            $this->notifyWebSocketChannel($user, $event);
        }

        if ($this->notification_channels['email']) {
            $this->notifyEmailChannel($user, $event);
        }

        return $this->dispatch('success:show', 'Notifications Has been sent')->to(NotificationTypes::class);
    }

    /**
     * @param mixed $user
     * @param mixed $event
     * @return mixed
     */
    private function notifyEmailChannel($user, $event)
    {
        try {
            Mail::to($user->email)->send(new EventReminderMail($event, $user));

            $this->dispatch('success:show', 'Email Notifications Has been sent')->to(NotificationTypes::class);

        } catch (\Exception $e) {
            \Log::error("Email sending failed: " . $e->getMessage()); // Log the error
            return false;
        }
    }

    /**
     * @param mixed $user
     * @param mixed $event
     * @return \Livewire\Features\SupportEvents\Event|null
     */
    private function notifyWebSocketChannel($user, $event)
    {
        if ($user->exists()) {
            // Generate a random date
            $randomDate = Carbon::now()->addDays(rand(1, 365))->format('D-m-Y');

            // Replace variables
            $message = str_replace(['{event}', '{date}'], [$event->title, $randomDate], $this->message);
            (new SendNotification($user, $message, $type = "event_reminder"))->notify();
        }
    }

    /**
     * @return \Livewire\Features\SupportEvents\Event|null
     */
    public function notifyAll()
    {
        if (empty(array_filter($this->notification_channels))) {
            return $this->dispatch('issues:show', 'Select Atleat One Channel')->to(NotificationTypes::class);
        }

        // get the events
        $events = Event::all();

        if ($events->count() > 0) {
            // Send each event's reminder
            foreach ($events as $event) {
                if ($this->notification_channels['web_sockets']) {
                    $this->notifyAllWebHookChannel($event->user, $event);
                }
        
                if ($this->notification_channels['email']) {
                    $this->notifyAllEmailChannel($event->user, $event);
                }
            }
        }

        return $this->dispatch('success:show', 'Notifications Has been sent')->to(NotificationTypes::class);

    }

    public function notifyAllWebHookChannel($user, $event)
    {
        // Generate a random date
        $randomDate = Carbon::now()->addDays(rand(1, 365))->format('D-m-Y');

        // Replace variables
        $message = str_replace(['{event}', '{date}'], [$event->title, $randomDate], $this->message);

        // BroadCast the message
        (new SendNotification($event->user, $message, $type = "event_reminder"))->notify();
    }

    /**
     * @param mixed $user
     * @param mixed $event
     * @return mixed
     */
    private function notifyAllEmailChannel($user, $event)
    {
        try {
            Mail::to($user->email)->send(new EventReminderMail($event, $user));

            $this->dispatch('success:show', 'Email Notifications Has been sent')->to(NotificationTypes::class);

        } catch (\Exception $e) {
            \Log::error("Email sending failed: " . $e->getMessage()); // Log the error
            return false;
        }
    }
}
