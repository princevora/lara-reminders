<?php

namespace App\Http\Controllers\Api\Admin\Notifications;

use App\BroadCastNotifications\SendNotification;
use App\Http\Controllers\Controller;
use App\Mail\EventReminderMail;
use App\Models\Event;
use App\Models\User;
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
                'user_id',
                'venue_id'
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
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function sendNotification(Request $request)
    {
        $typesValidator = Validator::make($request->all(), [
                'notification_type' => 'required|string|in:' . implode(',', array_keys($this->notifications_types)),
                'notification_channel' => 'required|string|in:' . implode(',', $this->notification_channels),
        ]);

        if($typesValidator->fails()){
            return response()->json($typesValidator->getMessageBag()->toArray(), 400, options: JSON_PRETTY_PRINT);
        }
        
        $this->notification_type = $request->notification_type;
        $this->notification_channel = $request->notification_channel;
        
        // // check if the required parameters of notification type is given..
        if(isset($this->notifications_types[$this->notification_type]['required'])){
            $rulesValidator = Validator::make($request->all(), $this->notifications_types[$this->notification_type]['required']);
            
            if($rulesValidator->fails()){
                return response()->json($rulesValidator->getMessageBag()->toArray(), 400, options: JSON_PRETTY_PRINT);
            }

            $this->request = $request;

            // call the appropriate functions
            $handler = $this->notifications_types[$this->notification_type]['handler'];
        
            // Call the handler
            return $this->$handler();
        }
    }

    /**
     * @return ?\Illuminate\Http\JsonResponse
     */
    private function eventReminderNotification()
    {
        $this->user = User::findOrFail($this->request->user_id);
        $this->event = Event::findOrFail($this->request->event_id);

        if($this->notification_channel == 'web_sockets'){
            return $this->webSocketsChannelForEventReminder();
        } else if($this->notification_channel == 'email') {
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
        if((new SendNotification($this->user, $message, $this->notification_type))->notify()){
            return response()->json([
                'status' => 200,
                'message' => 'Notification Sent Successfully'
            ], options: JSON_PRETTY_PRINT);
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
}
