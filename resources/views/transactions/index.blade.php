@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Transactions</h1>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">New Transaction</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Account No.</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Reference</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $tx)
            <tr>
                <td>{{ $tx->created_at }}</td>
                <td>{{ $tx->account->account_number }}</td>
                <td>{{ ucfirst($tx->type) }}</td>
                <td>₱{{ number_format($tx->amount, 2) }}</td>
                <td>{{ $tx->reference_number ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No transactions yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection