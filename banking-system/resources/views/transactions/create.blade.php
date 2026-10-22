<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Transaction</title>
    <link rel="stylesheet" href="{{ asset('css/bank.css') }}">
</head>
<body>
    <h1>New Transaction</h1>
    
    <nav aria-label="Main">
        <a href="{{ url('/dashboard-stats') }}">Dashboard</a>
        <a href="{{ route('transactions.index') }}" aria-current="page">Transactions</a>
        <a href="{{ route('transactions.create') }}" aria-current="page">New transaction</a>
    </nav>


    @if ($errors->any())
        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('transactions.store') }}">
        @csrf

        <label>Account</label><br>
        <select name="account_id">
            @foreach ($accounts as $account)
                <option value="{{ $account->id }}"
                    @selected(old('account_id') == $account->id)>
                    {{ $account->account_number }}
                    (Balance: {{ number_format($account->balance, 2) }})
                </option>
            @endforeach
        </select><br><br>

        <label>Type</label><br>
        <select name="type">
            <option value="deposit" @selected(old('type') == 'deposit')>Deposit</option>
            <option value="withdrawal" @selected(old('type') == 'withdrawal')>Withdrawal</option>
        </select><br><br>

        <label>Amount</label><br>
        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}"><br><br>

        <label>Reference number</label><br>
        <input type="text" name="reference_number" value="{{ old('reference_number') }}"><br><br>

        <label>Notes</label><br>
        <textarea name="notes">{{ old('notes') }}</textarea><br><br>

        <button type="submit">Submit</button>
    </form>

</body>
</html>