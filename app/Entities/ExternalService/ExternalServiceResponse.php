<?php

namespace App\Entities\ExternalService;

use Illuminate\Http\Client\Response;

class ExternalServiceResponse
{
    public Response $response;

    public ExternalServiceResponseData $data;

    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->data     = new ExternalServiceResponseData($response->json());
    }
}
