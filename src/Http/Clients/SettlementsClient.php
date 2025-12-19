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

    public function withdrawCustomer($identifier, array $payload): array
    {
        return $this->post("settlements/customers/{$identifier}/withdraw", $payload);
    }

    public function withdrawDirect(array $payload): array
    {
        return $this->post('settlements/withdraw-direct', $payload);
    }

    public function withdrawDirectCustomer($identifier, array $payload): array
    {
        return $this->post("settlements/customers/{$identifier}/withdraw-direct", $payload);
    }

    public function withdrawCustomerWallet($identifier, array $payload): array
    {
        return $this->post("settlements/customers/{$identifier}/transfer-wallet", $payload);
    }

    public function withdrawDirectBulk(array $payload): array
    {
        return $this->post('settlements/withdraw-direct-bulk', $payload);
    }
}
