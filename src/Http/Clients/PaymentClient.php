<?php

namespace DotaPay\LaravelSdk\Http\Clients;

class PaymentClient extends BaseClient
{
    public function request(array $payload): array
    {
        return $this->post('payment/request', $payload);
    }

    public function verify(array $payload): array
    {
        return $this->post('payment/verify', $payload);
    }

    public function status(string $reference): array
    {
        return $this->get("payment/status/{$reference}");
    }

    public function list(array $query = []): array
    {
        return $this->get('payment/list', $query);
    }

    public function initCustomer(array $payload): array
    {
        return $this->post('payment/init-customer', $payload);
    }

    public function verifyCustomer(array $payload): array
    {
        return $this->post('payment/verify-customer', $payload);
    }
}
