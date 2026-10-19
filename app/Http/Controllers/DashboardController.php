<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function stats()
    {
        $totalAccounts = Account::count();
        $totalFunds = Account::sum('balance');
        $todayTransactions = Transaction::whereDate('created_at', Carbon::today())->count();

        return response()->json([
            'total_accounts' => $totalAccounts,
            'total_bank' => number_format($totalFunds, 2),
            'tx_today' => $todayTransactions
        ]);
    }
}