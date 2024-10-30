<?php

namespace App\Entities\ExternalService;

class ExternalServiceResponse
{
    public string $status;
    public ExternalServiceData $data;

    public function __construct(array $data)
    {
        $this->status = $data['status'];
        $this->data = new ExternalServiceData($data['data']);
    }
}
