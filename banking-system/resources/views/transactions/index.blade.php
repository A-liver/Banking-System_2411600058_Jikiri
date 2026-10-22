<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <link rel="stylesheet" href="{{ asset('css/bank.css') }}">
</head>
<body>
    <h1>Transactions</h1>

    @if (session('success'))
        <p class="success-message">
            {{ session('success') }}
        </p>
    @endif

    <nav aria-label="Main">
        <a href="{{ url('/dashboard-stats') }}">Dashboard</a>
        <a href="{{ route('transactions.index') }}" aria-current="page">Transactions</a>
        <a href="{{ route('transactions.create') }}" aria-current="page">New transaction</a>
    </nav>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Account</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Reference</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $t)
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td>{{ $t->account->account_number }}</td>
                        <td>{{ ucfirst($t->type) }}</td>
                        <td>{{ number_format($t->amount, 2) }}</td>
                        <td>{{ $t->reference_number }}</td>
                        <td>{{ $t->created_at }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">No transactions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $transactions->links() }}
</body>
</html>