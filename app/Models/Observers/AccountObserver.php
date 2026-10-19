<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Account;
use App\Notifications\LowBalanceNotification;
use Illuminate\Support\Facades\DB;

class AccountObserver
{
    public function created(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $account = $transaction->account;
            $newBalance = $account->balance;

            match ($transaction->type) {
                'deposit'    => $newBalance += $transaction->amount,
                'withdrawal', 'transfer' => $newBalance -= $transaction->amount,
            };

            if ($newBalance < $account->minimum_balance || $newBalance < 0) {
                throw new \RuntimeException(
                    'Transaction denied: Below minimum balance or negative.'
                );
            }

            $account->update(['balance' => $newBalance]);

            $this->checkAndSendLowBalanceAlert($account);
        });
    }

    protected function checkAndSendLowBalanceAlert(Account $account): void
    {
        if ($account->balance <= $account->minimum_balance) {
            $recent = $account->user->notifications()
                ->where('type', LowBalanceNotification::class)
                ->where('created_at', '>=', now()->subDay())
                ->exists();

            if (!$recent) {
                $account->user->notify(new LowBalanceNotification($account));
            }
        }
    }
}