<?php

namespace EasyMeiTuan\Services;

use EasyMeiTuan\Client;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Store extends Client
{
    public function create(array $params): ResponseInterface
    {
        return $this->post('poi/save', $params);
    }
}
