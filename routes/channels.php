<?php

use App\Models\Owner;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('send-notification.{user_id}', function (User $user, $user_id) {
    return (int) $user->id === (int) $user_id;
});

Broadcast::channel('send-venue-request.{owner_id}', function ($user, $owner_id) {
    return $user->id === $owner_id;
}, ['guards' => ['owner']]);
