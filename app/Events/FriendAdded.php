<?php

namespace App\Events;

class FriendAdded
{
    public function __construct(public readonly int $userId, public readonly int $friendId)
    {
    }
}
