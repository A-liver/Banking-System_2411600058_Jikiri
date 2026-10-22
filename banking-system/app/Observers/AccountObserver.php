<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Transaction;
use App\Notifications\LowBalanceNotification;
use Illuminate\Support\Facades\DB;

class AccountObserver
{
    public function creating(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $account = Account::lockForUpdate()->findOrFail($transaction->account_id);

            $oldBalance = (float) $account->balance;
            $amount     = (float) $transaction->amount;

            $newBalance = $transaction->type === 'deposit'
                ? $oldBalance + $amount
                : $oldBalance - $amount; 

            if ($transaction->type !== 'deposit'
                && ($newBalance < 0 || $newBalance < (float) $account->minimum_balance)) {
                throw new \RuntimeException(
                    'Insufficient funds: balance cannot fall below the minimum balance of '
                    . number_format($account->minimum_balance, 2)
                );
            }

            $account->balance = $newBalance;
            $account->save();
        });
    }

    public function created(Transaction $transaction): void
    {
        if ($transaction->type === 'deposit') {
            return;
        }

        $account = $transaction->account()->first();

        $minimum    = (float) $account->minimum_balance;
        $newBalance = (float) $account->balance;
        $oldBalance = $newBalance + (float) $transaction->amount;
        
        if ($oldBalance > $minimum && $newBalance <= $minimum) {
            $account->user->notify(new LowBalanceNotification($account));
        }
    }
}