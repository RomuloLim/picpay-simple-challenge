<?php

namespace App\Enums;

enum ErrorCodes: string
{
    case INSUFFICIENT_FUNDS               = '01';
    case UNAUTHORIZED_BY_EXTERNAL_SERVICE = '02';
    case EXTERNAL_SERVICE_UNAVAILABLE     = '03';

    public function getMessage(): string
    {
        return match ($this) {
            self::INSUFFICIENT_FUNDS               => 'Insufficient funds on payment method.',
            self::UNAUTHORIZED_BY_EXTERNAL_SERVICE => 'Unauthorized by external service.',
            self::EXTERNAL_SERVICE_UNAVAILABLE     => 'External service is unavailable.',
        };
    }
}
