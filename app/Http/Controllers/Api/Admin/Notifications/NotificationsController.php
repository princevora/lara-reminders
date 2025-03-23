<?php

namespace App\Http\Controllers\Api\Admin\Notifications;

use App\BroadCastNotifications\SendNotification;
use App\BroadCastNotifications\SendVenueRequest;
use App\Http\Controllers\Controller;
use App\Mail\EventReminderMail;
use App\Mail\SystemNotificationMail;
use App\Models\Event;
use App\Models\Notification;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function Pest\Laravel\call;
use function PHPUnit\Framework\isCallable;

class NotificationsController extends Controller
{
    /**
     * Notification types mapping.
     *
     * @var array<string, array{icon: string, required?: string[], handler: string}>
     */
    private array $notifications_types = [
        'event_reminder' => [
            'icon' => 'calendar-days', //short names
            'required' => [
                'event_id' => 'required|exists:events,id',
                'user_id' => 'required|exists:users,id'
            ],
            'handler' => 'eventReminderNotification'
        ],
        'new_venue_request' => [
            'icon' => 'plus',
            'required' => [
                'user_id' => 'required|exists:users,id',
                'venue_id' => 'required|exists:venues,id',
            ],
            'handler' => 'newVenueRequestNotification'
        ],
        'system_notifications' => [
            'icon' => 'computer-desktop',
            'handler' => 'systemNotification'
        ]
    ];

    /**
     * @var array<int, string>
     */
    private array $notification_channels = [
        'web_sockets',
        'email'
    ];

    /**
     * @var ?string $notification_type
     */
    private ?string $notification_type;

    /**
     * @var ?string $notification_channel
     */
    private ?string $notification_channel;

    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * @var $user
     */
    private $user;

    /**
     * @var $event
     */
    private $event;

    /**
     * @var $venue 
     */
    private $venue;

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function sendNotification(Request $request)
    {
        $typesValidator = Validator::make($request->all(), [
            'notification_type' => 'required|string|in:' . implode(',', array_keys($this->notifications_types)),
            'notification_channel' => 'required|string|in:' . implode(',', $this->notification_channels),
        ]);

        if ($typesValidator->fails()) {
            return response()->json($typesValidator->getMessageBag()->toArray(), 400, options: JSON_PRETTY_PRINT);
        }

        $this->notification_type = $request->notification_type;
        $this->notification_channel = $request->notification_channel;

        // // check if the required parameters of notification type is given..
        if (isset($this->notifications_types[$this->notification_type]['required'])) {
            $rulesValidator = Validator::make($request->all(), $this->notifications_types[$this->notification_type]['required']);

            if ($rulesValidator->fails()) {
                return response()->json($rulesValidator->getMessageBag()->toArray(), 400, options: JSON_PRETTY_PRINT);
            }
        }

        $this->request = $request;

        // call the appropriate functions
        $handler = $this->notifications_types[$this->notification_type]['handler'];

        // Call the handler
        return $this->$handler();
    }

    /**
     * @return ?\Illuminate\Http\JsonResponse
     */
    private function eventReminderNotification()
    {
        $this->user = User::findOrFail($this->request->user_id);
        $this->event = Event::findOrFail($this->request->event_id);

        if ($this->notification_channel == 'web_sockets') {
            return $this->webSocketsChannelForEventReminder();
        } else if ($this->notification_channel == 'email') {
            return $this->emailChannelForEventReminder();
        }
    }

    /**
     * @return ?\Illuminate\Http\JsonResponse
     */
    private function webSocketsChannelForEventReminder()
    {
        $date = Carbon::now()->addDays(rand(1, 365))->format('D-m-Y');

        $message = "Event Reminder! The upcoming {$this->event->title} event on {$date} dont't forget to join it..";

        // Send notification
        if ((new SendNotification($this->user, $message, $this->notification_type))->notify()) {
            return response()->json([
                'status' => 200,
                'message' => 'Notification Sent Successfully'
            ], options: JSON_PRETTY_PRINT);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to send notification'
            ], 400, options: JSON_PRETTY_PRINT);
        }
    }

    /**
     * @return ?\Illuminate\Http\JsonResponse
     */
    public function emailChannelForEventReminder()
    {
        try {
            (new SendNotification($this->user))->notifyEmailChannel(mailClass: EventReminderMail::class, params: [
                $this->user,
                $this->event
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Email Notification Sent Successfully'
            ], options: JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to sent Email Notification'
            ], 400, options: JSON_PRETTY_PRINT);
        }
    }

    /**
     * @return ?\Illuminate\Http\JsonResponse
     */
    private function newVenueRequestNotification()
    {
        $this->user = User::findOrFail($this->request->user_id);
        $this->venue = Venue::findOrFail($this->request->venue_id);

        if ($this->notification_channel == 'web_sockets') {
            return $this->webHookChannelForNewVenueRequest();
        } else if ($this->notification_channel == 'email') {
            return $this->emailChannelForNewVenueRequest();
        }
    }

    /**
     * @return ?\Illuminate\Http\JsonResponse
     */
    private function webHookChannelForNewVenueRequest()
    {
        if ((new SendVenueRequest($this->venue->owner, $this->venue, $this->user))->notify()) {
            return response()->json([
                'status' => 200,
                'messae' => 'New Venue Booking Request Has been sent...'
            ], options: JSON_PRETTY_PRINT);
        } else {
            return response()->json([
                'status' => 400,
                'messae' => 'Unable to send New Venue Booking Request'
            ], options: JSON_PRETTY_PRINT);
        }
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    private function emailChannelForNewVenueRequest()
    {
        try {
            (new SendVenueRequest($this->venue->owner, $this->venue, $this->user))->notifyEmailChannel();

            return response()->json([
                'status' => 200,
                'message' => 'Venue Request Has been sent..'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to send the email'
            ], 400);
        }
    }

    /**
     * @return ?\Illuminate\Http\JsonResponse
     */
    private function systemNotification()
    {
        if ($this->notification_channel == 'web_sockets') {
            return $this->webSocketChannelForSystemNotifications();
        }
        if ($this->notification_channel == 'email') {
            return $this->emailChannelForSystemNotifications();
        }
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    private function webSocketChannelForSystemNotifications()
    {
        $users = User::all();
        $message = "Update! Our System Has Just Brought A New Feature";

        if ($this->request->has('message')) {
            $message = $this->request->message;
        }

        foreach ($users as $user) {
            // BroadCast the message
            (new SendNotification($user, $message, $type = "system_notifications"))->notify();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Notifications has been sent'
        ]);
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    private function emailChannelForSystemNotifications()
    {
        $users = User::all();

        $message = "Update! Our System Has Just Brought A New Feature";

        if ($this->request->has('message')) {
            $message = $this->request->message;
        }

        try {
            foreach ($users as $user) {
                // BroadCast the message
                (new SendNotification($user, $message))->notifyEmailChannel(SystemNotificationMail::class, [
                    $user,
                    $message
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Notifications has been sent'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to send notifications'
            ], 400);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param mixed $user_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function retriveNotifications(Request $request, $user_id)
    {
        // find user
        User::findOrFail($user_id);

        return response()->json([
            'data' => Notification::where('user_id', $user_id)->latest()->get()
        ], options: JSON_PRETTY_PRINT);
    }

    public function markAsRead(Request $request, $notification_id)
    {
        $notification = Notification::where('_id', $notification_id)->firstOrFail();

        if (is_null($notification->read_at)) {
            $notification->update([
                'read_at' => now()
            ]);
        }

        return response()->json([
            'response' => 'Marked As Read'
        ], JSON_PRETTY_PRINT); // ✅ Correct syntax
    }
}
