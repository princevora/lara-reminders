<?php

namespace App\Livewire\Users;

use Livewire\Attributes\On;
use Livewire\Component;

class Notifications extends Component
{
    /**
     * @var string
     */
    public string $user_id;

    public function mount()
    {
        $this->user_id = auth()->user()->id;
    }

    #[On('echo-private:send-notification.{user_id},SendNotificationEvent')]
    public function listenForMessage($data)
    {
        dd($data);
    }
}
