<?php

namespace App\BroadCastNotifications;

use App\Models\VenueRequest;

class OwnerNotifications
{
    /**
     * @var $owner 
     */
    public $owner;
    
    /**
     * @param mixed $owner
     */
    public function __construct($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return bool
     */
    public function markAllRequestsAsRead()
    {
        // Find venue requests of the owner
        return VenueRequest::whereHas('venue', function($query) {
            $query->where('owner_id', $this->owner->id);
        })->update([
            'read_at' => now()
        ]);
    }
}
