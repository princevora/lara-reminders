<?php

namespace App\BroadCastNotifications;

use App\Events\SendNotificationEvent;
use App\Mail\EventReminderMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use function Pest\Laravel\instance;

class SendNotification
{
    /**
     * @var $users 
     */
    public $users;

    /**
     * @var string $message
     */
    public string $message = '';

    /**
     * @var string $type
     */
    public ?string $type;

    /**
     * @param mixed $users
     */
    public function __construct($users = [], string $message = "", $type = null)
    {
        $this->users = $users instanceof Collection ? $users : collect([$users]);
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function notify(): bool
    {
        if(empty($this->users) || is_null($this->users)){
            return false;
        }

        foreach ($this->users as $user) {
            // Store the notifications
            Notification::create([
                'user_id' => $user->id,
                'message' => $this->message,
                'type' => $this->type,
                'read_at' => null
            ]);

            broadcast(new SendNotificationEvent($this->message, $user->id))->toOthers();
        }
        
        return true;
    }

    public function notifyEmailChannel($mailClass, array $params = [])
    {
        Mail::to($this->users->first()->email)->send(new $mailClass(...$params));
    }
}
