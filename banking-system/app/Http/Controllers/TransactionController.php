<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('account')->latest()->paginate(15);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $accounts = Account::all();
        return view('transactions.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id'       => ['required', 'exists:accounts,id'],
            'type'             => ['required', 'in:deposit,withdrawal'],
            'amount'           => ['required', 'numeric', 'min:1'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'notes'            => ['nullable', 'string'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($validator->errors()->isNotEmpty() || $request->type !== 'withdrawal') {
                return;
            }

            $account = Account::find($request->account_id);

            if (($account->balance - $request->amount) < $account->minimum_balance) {
                $validator->errors()->add(
                    'amount',
                    'Insufficient funds. Withdrawal would drop the balance below the minimum of '
                    . number_format($account->minimum_balance, 2) . '.'
                );
            }
        });

        $data = $validator->validate();

        try {
            DB::transaction(function () use ($data) {
                Transaction::create($data);
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->withErrors(['amount' => $e->getMessage()]);
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction completed successfully.');
    }
}