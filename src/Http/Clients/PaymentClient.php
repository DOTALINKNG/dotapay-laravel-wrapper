<?php

namespace DotaPay\LaravelSdk\Http\Clients;

use DotaPay\LaravelSdk\Data\Payment\PaymentCollection;
use DotaPay\LaravelSdk\Data\Payment\PaymentResponse;

class PaymentClient extends BaseClient
{
    /**
     * Request a payment from a customer's wallet.
     *
     * @param array{
     *     customer: string,
     *     amount: int|float,
     *     reference: string,
     *     description?: string,
     *     meta?: array<string, mixed>,
     * } $payload
     */
    public function request(array $payload): PaymentResponse
    {
        return PaymentResponse::fromArray($this->post('payment/request', $payload));
    }

    /**
     * Verify a payment using OTP or PIN.
     *
     * @param array{
     *     reference: string,
     *     otp?: string,
     *     pin?: string,
     * } $payload
     */
    public function verify(array $payload): PaymentResponse
    {
        return PaymentResponse::fromArray($this->post('payment/verify', $payload));
    }

    /**
     * Get the status of a payment by reference.
     */
    public function status(string $reference): PaymentResponse
    {
        return PaymentResponse::fromArray($this->get("payment/status/{$reference}"));
    }

    /**
     * List all payments.
     *
     * @param array{
     *     page?: int,
     *     per_page?: int,
     *     status?: string,
     *     customer?: string,
     *     from?: string,
     *     to?: string,
     * } $query
     */
    public function list(array $query = []): PaymentCollection
    {
        return PaymentCollection::fromArray($this->get('payment/list', $query));
    }

    /**
     * Initialize a customer payment.
     *
     * @param array{
     *     customer: string,
     *     amount: int|float,
     *     reference: string,
     *     description?: string,
     *     meta?: array<string, mixed>,
     * } $payload
     */
    public function initCustomer(array $payload): PaymentResponse
    {
        return PaymentResponse::fromArray($this->post('payment/init-customer', $payload));
    }

    /**
     * Verify a customer payment.
     *
     * @param array{
     *     reference: string,
     *     otp?: string,
     *     pin?: string,
     * } $payload
     */
    public function verifyCustomer(array $payload): PaymentResponse
    {
        return PaymentResponse::fromArray($this->post('payment/verify-customer', $payload));
    }
}
