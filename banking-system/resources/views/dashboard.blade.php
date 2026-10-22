
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/bank.css') }}">
</head>
<body>
    <h1>Bank Dashboard</h1>

    <nav aria-label="Main navigation">
        <a href="{{ url('/dashboard-stats') }}" aria-current="page">Dashboard</a>
        <a href="{{ route('transactions.index') }}">Transactions</a>
        <a href="{{ route('transactions.create') }}">New transaction</a>
    </nav>

    <div class="stats">
        <div class="stat">
            <p class="label">Total accounts</p>
            <p class="value" id="total-accounts">-</p>
        </div>

        <div class="stat">
            <p class="label">Total money in bank</p>
            <p class="value" id="total-money">-</p>
        </div>

        <div class="stat">
            <p class="label">Transactions today</p>
            <p class="value" id="total-today">-</p>
        </div>
    </div>

    <p class="status" id="status" role="status">
        Loading dashboard statistics...
    </p>

    <script>
        async function loadStats() {
            const status = document.getElementById('status');

            try {
                const res = await fetch('/api/dashboard/stats', {
                    headers: { 'Accept': 'application/json' }
                });

                if (!res.ok) {
                    throw new Error('Failed to load dashboard statistics.');
                }

                const data = await res.json();

                document.getElementById('total-accounts').textContent =
                    Number(data.total_accounts).toLocaleString();

                document.getElementById('total-money').textContent =
                    Number(data.total_money).toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                document.getElementById('total-today').textContent =
                    Number(data.total_transactions_today).toLocaleString();

                status.textContent = 'Last updated: ' +
                    new Date().toLocaleTimeString();
            } catch (e) {
                console.error('Polling failed:', e);
                status.textContent =
                    'Unable to load statistics. Please try again later.';
            }
        }

        loadStats();
        setInterval(loadStats, 30000);
    </script>
</body>
</html>
