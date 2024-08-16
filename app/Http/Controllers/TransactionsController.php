<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    public function store(CreateTransactionRequest $request)
    {
        $transactionState = DB::transaction(function () use ($request) {
            $transaction = Transaction::create($request->validated())->load('sender', 'receiver');

            if ($transaction->sender->balance >= $transaction->amount) {
                $transaction->sender->decrement('balance', $transaction->amount);
                $transaction->receiver->increment('balance', $transaction->amount);
                $transaction->update([
                    'is_successful' => true,
                    'completed_at'  => now(),
                ]);
            } else {
                $transaction->update([
                    'is_successful'  => false,
                    'failure_reason' => 'Insufficient balance',
                    'completed_at'   => now(),
                ]);
            }

            return $transaction->refresh();
        });

        return response()->json($transactionState, Response::HTTP_ACCEPTED);
    }
}
