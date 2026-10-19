<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('account')->latest()->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $accounts = Account::all();
        return view('transactions.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_number' => 'required|exists:accounts,account_number',
            'type' => 'required|in:deposit,withdrawal,transfer',
            'amount' => 'required|numeric|min:0.01',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $account = Account::where('account_number', $validated['account_number'])->firstOrFail();

        DB::transaction(function () use ($account, $validated) {
            if ($validated['type'] === 'deposit') {
                $account->increment('balance', $validated['amount']);
            } elseif ($validated['type'] === 'withdrawal') {
                if ($account->balance < $validated['amount']) {
                    throw new \Exception('Insufficient balance');
                }
                $account->decrement('balance', $validated['amount']);
            }

            Transaction::create([
                'account_id' => $account->id,
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'reference_number' => $validated['reference_number'],
                'notes' => $validated['notes'],
            ]);
        });

        return redirect()->route('transactions.index')->with('success', 'Transaction successful!');
    }
}