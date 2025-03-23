<?php

namespace App\Livewire\Users;

use App\Models\Venue;
use Livewire\Component;
use App\BroadCastNotifications\SendVenueRequest as SendVenueRequestBroadcaster;

class SendVenueRequest extends Component
{
    /**
     * @var $venues 
     */
    public $venues;
    
    /**
     * @var string $message 
     */
    private string $message = "Hello {owner}! I want to request for the {venue_name} Venue";

    /**
     * @var $user 
     */
    public $user;

    /**
     * @var ?string $response_message 
     */
    public ?string $response_message = null;

    /**
     * @var int $message_type
     */
    public $message_type = 0;

    /**
     * @return void
     */
    public function mount()
    {
        $this->venues = Venue::all();
        $this->user = auth()->guard('web')->user();
    }

    /**
     * @param mixed $id
     * @return void
     */
    public function requestVenue($id)
    {
        $venue = Venue::find($id);
        $message = str_replace([
            '{owner}', 
            '{venue_name}'
        ], [
            $venue->owner->name,
            $venue->name 
        ], $this->message);

        if($venue){
            (new SendVenueRequestBroadcaster($venue->owner, $venue, $this->user, $message))->notify();
     
            $this->response_message = "Reuqest Has been sent";
            $this->message_type = 1;

            $this->dispatch('clearMessage');
        }
    }
}
