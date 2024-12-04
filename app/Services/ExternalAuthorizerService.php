<?php

namespace App\Services;

use App\Entities\ExternalService\ExternalServiceResponse;
use App\Enums\ErrorCodes;
use Illuminate\Support\Facades\Http;

class ExternalAuthorizerService
{
    public function __construct() {}

    public static function checkTransaction(): true|ErrorCodes
    {
        $responseData = new ExternalServiceResponse(Http::get(config('external_services.authorization_url')));

        if ($responseData->response->failed()) {
            return ErrorCodes::EXTERNAL_SERVICE_UNAVAILABLE;
        } elseif (! $responseData->data->authorization) {
            return ErrorCodes::UNAUTHORIZED_BY_EXTERNAL_SERVICE;
        }

        return true;
    }
}
