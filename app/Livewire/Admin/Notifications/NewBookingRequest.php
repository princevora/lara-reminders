<?php

namespace App\Livewire\Admin\Notifications;

use App\BroadCastNotifications\SendVenueRequest;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class NewBookingRequest extends Component
{
    /**
     * @var $users 
     */
    public $users;

    /**
     * @var $venues
     */
    public $venues;

    /**
     * @var mixed $venue 
     */
    public $venue = null;

    /**
     * @var mixed $user_id
     */
    public $user = null;

    /** */
    public ?string $response_message;
       
    /**
     * @var ?int $message_type 
     */
    public ?int $message_type = null;

    /**
     * @var array<string, bool>
     */
    public $notification_channels = [
        'web_sockets' => true,
        'email' => false,
        // 'push' => false
    ];

    /**
     * @return void
     */
    public function mount()
    {
        $this->users = User::all();
        $this->venues = Venue::all();
    }

    /**
     * @param mixed $venue_id
     * @return void
     */
    public function selectVenue($venue_id)
    {
        $venue = Venue::find($venue_id);
        $this->venue = $venue;
    }

    /**
     * @param mixed $user_id
     * @return void
     */
    public function selectUser($user_id)
    {
        $user = User::find($user_id);
        $this->user = $user;
    }

    /**
     * @return void
     * @throws ?ValidationException
     */
    public function sendVenueRequest()
    {
        if (empty(array_filter($this->notification_channels))) {
            throw ValidationException::withMessages(['form_message' => 'Select Atleat One Channel']);
        }

        if (is_null($this->venue) || is_null($this->user)) {
            throw ValidationException::withMessages(['form_message' => 'User And Venue Must be selected']);
        }
        
        if($this->notification_channels['web_sockets']){
            (new SendVenueRequest($this->venue->owner, $this->venue, $this->user))->notify();
        }
        if($this->notification_channels['email']){
            try {
                (new SendVenueRequest($this->venue->owner, $this->venue, $this->user))->notifyEmailChannel();
            } catch (\Throwable $th) {
                throw ValidationException::withMessages(['form_message' => 'Unable to send the email']);
            }
        }

        $this->response_message = "Reuqest Has been sent";
        $this->message_type = 1;

        $this->dispatch('clearMessage');
    }
}
