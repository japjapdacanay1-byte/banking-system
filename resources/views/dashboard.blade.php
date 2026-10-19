@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Banking Dashboard</h1>
    <div class="card mt-4">
        <div class="card-body">
            <p><strong>Total Accounts:</strong> <span id="total-accounts">0</span></p>
            <p><strong>Total Funds in Bank:</strong> ₱<span id="total-bank">0.00</span></p>
            <p><strong>Transactions Today:</strong> <span id="tx-today">0</span></p>
        </div>
    </div>
</div>

<script>
function fetchStats() {
    fetch('/api/dashboard/stats')
        .then(res => res.json())
        .then(data => {
            document.getElementById('total-accounts').textContent = data.total_accounts;
            document.getElementById('total-bank').textContent = data.total_bank;
            document.getElementById('tx-today').textContent = data.tx_today;
        })
        .catch(err => console.error('Error:', err));
}

fetchStats();
// Auto-refresh every 30 seconds
setInterval(fetchStats, 30000);
</script>
@endsection