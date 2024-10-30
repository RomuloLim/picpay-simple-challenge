<?php

namespace App\Entities\ExternalService;

class ExternalServiceData
{
    public bool $authorization;

    public function __construct(array $data)
    {
        $this->authorization = $data['authorization'];
    }
}
