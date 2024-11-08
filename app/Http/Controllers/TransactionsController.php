<?php

namespace App\Http\Controllers;

use App\Entities\ExternalService\ExternalServiceResponse;
use App\Enums\ErrorCodes;
use App\Exceptions\ExternalSericeException;
use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\{DB, Http};

class TransactionsController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function store(CreateTransactionRequest $request)
    {
        $errorCode        = null;
        $transactionState = DB::transaction(function () use ($request, $errorCode) {
            $transaction = Transaction::create($request->validated())->load('sender', 'receiver');

            if ($transaction->sender->balance >= $transaction->amount) {
                $responseData = new ExternalServiceResponse(Http::get(config('external_services.authorization_url'))); //todo: convert to service

                if ($responseData->response->failed()) {
                    $errorCode = ErrorCodes::EXTERNAL_SERVICE_UNAVAILABLE;
                } elseif (! $responseData->data->authorization) {
                    $errorCode = ErrorCodes::UNAUTHORIZED_BY_EXTERNAL_SERVICE;
                }

                if ($errorCode) {
                    $transaction->update([
                        'is_successful'  => false,
                        'failure_reason' => $errorCode->getMessage(),
                        'error_code'     => $errorCode,
                        'completed_at'   => now(),
                    ]);

                    return $transaction->refresh();
                }

                $transaction->sender->decrement('balance', $transaction->amount);
                $transaction->receiver->increment('balance', $transaction->amount);

                $transaction->update([
                    'is_successful' => true,
                    'completed_at'  => now(),
                ]);
            } else {
                $transaction->update([
                    'is_successful'  => false,
                    'failure_reason' => 'Insufficient funds on payment method.',
                    'error_code'     => ErrorCodes::INSUFFICIENT_FUNDS,
                    'completed_at'   => now(),
                ]);
            }

            return $transaction->refresh();
        });

        if (! $transactionState->is_successful) {
            return response()->json($transactionState, Response::HTTP_BAD_REQUEST);
        }

        match ($errorCode) {
            ErrorCodes::EXTERNAL_SERVICE_UNAVAILABLE     => throw ExternalSericeException::serviceUnavailable(),
            ErrorCodes::UNAUTHORIZED_BY_EXTERNAL_SERVICE => throw ExternalSericeException::unauthorized(),
            default                                      => null,
        };

        return response()->json($transactionState, Response::HTTP_ACCEPTED);
    }
}
