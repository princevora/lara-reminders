<?php

namespace App\Livewire\Owner;

use App\BroadCastNotifications\OwnerNotifications;
use App\Models\Notification;
use App\Models\VenueRequest;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class Requests extends Component
{
    /**
     * @var array<string, string>
     */
    public array $requests_types = [
        'event_reminder' => 'calendar-days',
        'new_venue_request' => 'plus',
        'system_requests' => 'computer-desktop'
    ];

    /**
     * @var $requests 
     */
    public $requests;

    /**
     * @var int $unreadRequests
     */
    public int $unreadRequests;

    /**
     * @var $owner_id 
     */
    public $owner_id;

    /**
     * @var $owner 
     */
    public $owner;

    /**
     * @return void
     */
    public function mount()
    {
        $this->owner = auth()->guard('owner')->user();
        $this->owner_id = $this->owner->id;
        $this->updateRequests();
    }

    /**
     * @return void
     */
    private function updateRequests()
    {
        $this->requests = $this->getRequests();
        $this->unreadRequests = $this->requests->whereNull('read_at')->count();
    }

    /**
     * @return Collection<int, VenueRequest>
     */
    private function getRequests()
    {
        return VenueRequest::whereHas('venue', function($query) {
            $query->where('owner_id', $this->owner_id);
        })->latest()->get();

    }

    /**
     * @param mixed $data
     * @return void
     */
    #[On('echo-private:send-venue-request.{owner_id},SendVenueRequestEvent')]
    public function listenForMessage($data)
    {
        $this->updateRequests();
    }

    /**
     * @return void
     */
    public function markAllAsRead()
    {
        // mark all as read..
        (new OwnerNotifications($this->owner))->markAllRequestsAsRead();

        $this->updateRequests();
    }
}
