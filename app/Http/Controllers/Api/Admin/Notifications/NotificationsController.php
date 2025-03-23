<?php

namespace App\Http\Controllers\Api\Admin\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * Notification types mapping.
     *
     * @var array<string, array{icon: string, required?: string[]}>
     */
    private array $notifications_types = [
        'event_reminder' => [
            'icon' => 'calendar-days', //short names
            'required' => [
                'event_id',
                'user_id'
            ]
        ],
        'new_venue_request' => [
            'icon' => 'plus',
            'required' => [
                'user_id',
                'venue_id'
            ]
        ],
        'system_notifications' => [
            'icon' => 'computer-desktop',
        ]
    ];

    /**
     * @var array<int, string>
     */
    private array $notification_channels = [
        'web_sockets',
        'email'
    ];

    public function sendNotification(Request $request)
    {

    }
}
