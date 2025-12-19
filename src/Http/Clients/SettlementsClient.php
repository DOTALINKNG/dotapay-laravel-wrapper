<?php

namespace DotaPay\LaravelSdk\Http\Clients;

class SettlementsClient extends BaseClient
{
    public function index(array $query = []): array
    {
        return $this->get('settlements', $query);
    }

    public function withdraw(array $payload): array
    {
        return $this->post('settlements/withdraw', $payload);
    }

    public function withdrawCustomer(array $payload): array
    {
        return $this->post('settlements/withdraw-customer', $payload);
    }

    public function withdrawDirect(array $payload): array
    {
        return $this->post('settlements/withdraw-direct', $payload);
    }

    public function withdrawDirectCustomer(array $payload): array
    {
        return $this->post('settlements/withdraw-direct-customer', $payload);
    }

    public function withdrawDirectBulk(array $payload): array
    {
        return $this->post('settlements/withdraw-direct-bulk', $payload);
    }
}
