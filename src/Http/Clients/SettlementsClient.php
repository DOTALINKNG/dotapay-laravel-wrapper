<?php

namespace DotaPay\LaravelSdk\Http\Clients;

use DotaPay\LaravelSdk\Data\Settlements\SettlementCollection;
use DotaPay\LaravelSdk\Data\Settlements\WithdrawalResponse;

class SettlementsClient extends BaseClient
{
    /**
     * List all settlements.
     *
     * @param array{
     *     page?: int,
     *     per_page?: int,
     *     status?: string,
     *     type?: string,
     *     from?: string,
     *     to?: string,
     * } $query
     */
    public function index(array $query = []): SettlementCollection
    {
        return SettlementCollection::fromArray($this->get('settlements', $query));
    }

    /**
     * Withdraw from the main wallet.
     *
     * @param array{
     *     amount: int|float,
     *     bank_code: string,
     *     account_number: string,
     *     reference?: string,
     *     narration?: string,
     *     meta?: array<string, mixed>,
     * } $payload
     */
    public function withdraw(array $payload): WithdrawalResponse
    {
        return WithdrawalResponse::fromArray($this->post('settlements/withdraw', $payload));
    }

    /**
     * Withdraw from a customer's wallet.
     *
     * @param array{
     *     amount: int|float,
     *     bank_code: string,
     *     account_number: string,
     *     reference?: string,
     *     narration?: string,
     *     meta?: array<string, mixed>,
     * } $payload
     */
    public function withdrawCustomer(string|int $identifier, array $payload): WithdrawalResponse
    {
        return WithdrawalResponse::fromArray($this->post("settlements/customers/{$identifier}/withdraw", $payload));
    }

    /**
     * Withdraw directly from the main wallet (instant settlement).
     *
     * @param array{
     *     amount: int|float,
     *     bank_code: string,
     *     account_number: string,
     *     reference?: string,
     *     narration?: string,
     *     meta?: array<string, mixed>,
     * } $payload
     */
    public function withdrawDirect(array $payload): WithdrawalResponse
    {
        return WithdrawalResponse::fromArray($this->post('settlements/withdraw-direct', $payload));
    }

    /**
     * Withdraw directly from a customer's wallet (instant settlement).
     *
     * @param array{
     *     amount: int|float,
     *     bank_code: string,
     *     account_number: string,
     *     reference?: string,
     *     narration?: string,
     *     meta?: array<string, mixed>,
     * } $payload
     */
    public function withdrawDirectCustomer(string|int $identifier, array $payload): WithdrawalResponse
    {
        return WithdrawalResponse::fromArray($this->post("settlements/customers/{$identifier}/withdraw-direct", $payload));
    }

    /**
     * Transfer from a customer's wallet to another wallet.
     *
     * @param array{
     *     amount: int|float,
     *     destination_wallet: string,
     *     reference?: string,
     *     narration?: string,
     *     meta?: array<string, mixed>,
     * } $payload
     */
    public function withdrawCustomerWallet(string|int $identifier, array $payload): WithdrawalResponse
    {
        return WithdrawalResponse::fromArray($this->post("settlements/customers/{$identifier}/transfer-wallet", $payload));
    }

    /**
     * Bulk withdraw directly (instant settlement to multiple accounts).
     *
     * @param array{
     *     transfers: array<array{
     *         amount: int|float,
     *         bank_code: string,
     *         account_number: string,
     *         reference?: string,
     *         narration?: string,
     *     }>,
     *     meta?: array<string, mixed>,
     * } $payload
     */
    public function withdrawDirectBulk(array $payload): WithdrawalResponse
    {
        return WithdrawalResponse::fromArray($this->post('settlements/withdraw-direct-bulk', $payload));
    }
}
