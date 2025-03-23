<?php

use App\Models\Owner;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('send-notification.{user_id}', function ($user, $user_id) {
    return (int) $user->id === (int) $user_id;
});

Broadcast::channel('send-venue-request.{owner_id}', function (Owner $owner, $owner_id) {
    return (int) $owner->id === (int) $owner_id;
});
