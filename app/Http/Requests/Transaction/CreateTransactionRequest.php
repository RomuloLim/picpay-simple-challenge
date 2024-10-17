<?php

namespace App\Http\Requests\Transaction;

use App\Rules\CanTransfer;
use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sender_id' => [
                'required',
                'exists:users,id',
                new CanTransfer,
                'different::receiver_id',
            ],
            'receiver_id' => ['required', 'exists:users,id', 'different:sender_id'],
            'amount'      => ['required', 'numeric', 'min:1'],
            'description' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
