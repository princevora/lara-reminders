<?php

namespace App\BroadCastNotifications;

use App\Events\SendVenueRequestEvent;
use App\Mail\VenueBookingRequest;
use App\Models\VenueRequest;
use Mail;

class SendVenueRequest
{
    /**
     * @var $owner 
     */
    public $owner;

     /**
     * @var string $message 
     */
    public string $message = "Hello {owner}! I want to request for the {venue_name} Venue";

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
    public function __construct($owner, $venue, $user)
    {
        $this->owner = $owner;
        $this->venue = $venue;
        $this->user = $user;
    }

    public function notify()
    {
        $message = str_replace([
            '{owner}', 
            '{venue_name}'
        ], [
            $this->venue->owner->name,
            $this->venue->name 
        ], $this->message);

        VenueRequest::create([
            'user_id' => $this->user->id,
            'venue_id' => $this->venue->id,
            'message' => $message,
            'read_at' => null
        ]);

        broadcast(new SendVenueRequestEvent($this->owner))->toOthers();
    }

    /**
     * @return \Illuminate\Mail\SentMessage|null
     */
    public function notifyEmailChannel()
    {
        Mail::to($this->owner->email)->send(new VenueBookingRequest(
            $this->owner, 
            $this->user, 
            $this->venue
        ));
    }
}
