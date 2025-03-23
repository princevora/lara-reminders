<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VenueBookingRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var $owner 
     */
    public $owner;
    
    /**
     * @var $user 
     */
    public $user;

    /**
     * @var $venue 
     */
    public $venue;
    
    public function __construct($owner, $user, $venue)
    {
        $this->owner = $owner;
        $this->user = $user;
        $this->venue = $venue;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Venue Booking Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.venue-booking-request',
            with: [
                'owner' => $this->owner,
                'user' => $this->user,
                'venue' => $this->venue
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
