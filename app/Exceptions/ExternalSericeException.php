<?php

namespace App\Exceptions;

use Nette\Utils\JsonException;

class ExternalSericeException extends JsonException
{
    public static function serviceUnavailable($status = 503): self
    {
        return new self('External service is unavailable.', $status);
    }

    public static function unauthorized($status = 401): self
    {
        return new self('Unauthorized by external service.', $status);
    }
}
