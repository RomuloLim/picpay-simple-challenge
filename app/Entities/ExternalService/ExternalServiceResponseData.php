<?php

namespace App\Entities\ExternalService;

class ExternalServiceResponseData
{
    public string $status;

    public bool $authorization;

    public function __construct(array $data)
    {
        $this->status        = $data['status'];
        $this->authorization = $data['data']['authorization'];
    }
}
