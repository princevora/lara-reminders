<?php

namespace App\Livewire\Users;

use App\Models\Venue;
use Livewire\Component;

class SendVenueRequest extends Component
{
    /**
     * @var $venues 
     */
    public $venues;
    
    public function mount()
    {
        $this->venues = Venue::all();
    }

    public function requestVenue($id)
    {
        $venue = Venue::find($id);

        if($venue){
            // $venue->owner->id
        }
    }
}
