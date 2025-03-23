<?php

namespace App\BroadCastNotifications;

use App\Events\SendVenueRequestEvent;
use App\Models\VenueRequest;

class SendVenueRequest
{
    /**
     * @var $owner 
     */
    public $owner;

    /**
     * @var string $message
     */
    public string $message;

    /**
     * @var $venue 
     */
    public $venue;

    /**
     * @var $user
     */
    public $user;

    /**
     * @param mixed $owner
     * @param mixed $venue
     * @param mixed $message
     * @param mixed $user
     */
    public function __construct($owner, $venue, $user, $message)
    {
        $this->owner = $owner;
        $this->message = $message;
        $this->venue = $venue;
        $this->user = $user;
    }

    public function notify()
    {
        VenueRequest::create([
            'user_id' => $this->user->id,
            'venue_id' => $this->venue->id,
            'message' => $this->message,
            'read_at' => null
        ]);

        broadcast(new SendVenueRequestEvent($this->owner))->toOthers();
    }
}
