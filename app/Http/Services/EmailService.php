<?php


namespace App\Http\Services;


use App\Item;
use App\User;
use Carbon\Carbon;
use Exception;

class EmailService
{

    /**
     * @param User $user
     * @param Item $item
     * @throws Exception
     */
    public function send(User $user, Item $item): void
    {
        throw new Exception('Not yet implemented');
    }

    public function shouldSend(User $user): bool
    {
        return !is_null($user) && Carbon::now()->subYears(18)->isAfter($user->birthday);
    }
}