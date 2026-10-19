<?php

namespace App\Notifications;

use App\Models\Account;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class LowBalanceNotification extends Notification
{
    public function __construct(public Account $account) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => "Your account ({$this->account->account_number}) balance is low.",
            'balance' => $this->account->balance,
            'minimum_required' => $this->account->minimum_balance,
        ];
    }
}