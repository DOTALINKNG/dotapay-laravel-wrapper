<?php

namespace DotaPay\LaravelSdk\Http\Clients;

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
     * @return array{
     *     data: array<array{
     *         id: int,
     *         code: string,
     *         reference: string,
     *         amount: float,
     *         amount_kobo: int,
     *         status: string,
     *         type: string,
     *         bank_code: string|null,
     *         account_number: string|null,
     *         account_name: string|null,
     *         created_at: string,
     *     }>,
     *     meta: array{current_page: int, last_page: int, per_page: int, total: int},
     * }
     */
    public function index(array $query = []): array
    {
        return $this->get('settlements', $query);
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
     * @return array{
     *     data: array{
     *         settlement?: array{
     *             id: int,
     *             code: string,
     *             reference: string,
     *             amount: float,
     *             amount_kobo: int,
     *             status: string,
     *             type: string,
     *             bank_code: string|null,
     *             account_number: string|null,
     *             account_name: string|null,
     *             created_at: string,
     *         },
     *         id?: int,
     *         code?: string,
     *         reference?: string,
     *         amount?: float,
     *         status?: string,
     *     },
     *     message?: string,
     * }
     */
    public function withdraw(array $payload): array
    {
        return $this->post('settlements/withdraw', $payload);
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
     * @return array{
     *     data: array{
     *         settlement?: array{
     *             id: int,
     *             code: string,
     *             reference: string,
     *             amount: float,
     *             amount_kobo: int,
     *             status: string,
     *             type: string,
     *             bank_code: string|null,
     *             account_number: string|null,
     *             account_name: string|null,
     *             created_at: string,
     *         },
     *         id?: int,
     *         code?: string,
     *         reference?: string,
     *         amount?: float,
     *         status?: string,
     *     },
     *     message?: string,
     * }
     */
    public function withdrawCustomer(string|int $identifier, array $payload): array
    {
        return $this->post("settlements/customers/{$identifier}/withdraw", $payload);
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
     * @return array{
     *     data: array{
     *         settlement?: array{
     *             id: int,
     *             code: string,
     *             reference: string,
     *             amount: float,
     *             amount_kobo: int,
     *             status: string,
     *             type: string,
     *             bank_code: string|null,
     *             account_number: string|null,
     *             account_name: string|null,
     *             created_at: string,
     *         },
     *         id?: int,
     *         code?: string,
     *         reference?: string,
     *         amount?: float,
     *         status?: string,
     *     },
     *     message?: string,
     * }
     */
    public function withdrawDirect(array $payload): array
    {
        return $this->post('settlements/withdraw-direct', $payload);
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
     * @return array{
     *     data: array{
     *         settlement?: array{
     *             id: int,
     *             code: string,
     *             reference: string,
     *             amount: float,
     *             amount_kobo: int,
     *             status: string,
     *             type: string,
     *             bank_code: string|null,
     *             account_number: string|null,
     *             account_name: string|null,
     *             created_at: string,
     *         },
     *         id?: int,
     *         code?: string,
     *         reference?: string,
     *         amount?: float,
     *         status?: string,
     *     },
     *     message?: string,
     * }
     */
    public function withdrawDirectCustomer(string|int $identifier, array $payload): array
    {
        return $this->post("settlements/customers/{$identifier}/withdraw-direct", $payload);
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
     * @return array{
     *     data: array{
     *         settlement?: array{
     *             id: int,
     *             code: string,
     *             reference: string,
     *             amount: float,
     *             amount_kobo: int,
     *             status: string,
     *             type: string,
     *             bank_code: string|null,
     *             account_number: string|null,
     *             account_name: string|null,
     *             created_at: string,
     *         },
     *         id?: int,
     *         code?: string,
     *         reference?: string,
     *         amount?: float,
     *         status?: string,
     *     },
     *     message?: string,
     * }
     */
    public function withdrawCustomerWallet(string|int $identifier, array $payload): array
    {
        return $this->post("settlements/customers/{$identifier}/transfer-wallet", $payload);
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
     * @return array{
     *     data: array{
     *         settlement?: array{
     *             id: int,
     *             code: string,
     *             reference: string,
     *             amount: float,
     *             amount_kobo: int,
     *             status: string,
     *             type: string,
     *             bank_code: string|null,
     *             account_number: string|null,
     *             account_name: string|null,
     *             created_at: string,
     *         },
     *         id?: int,
     *         code?: string,
     *         reference?: string,
     *         amount?: float,
     *         status?: string,
     *     },
     *     message?: string,
     * }
     */
    public function withdrawDirectBulk(array $payload): array
    {
        return $this->post('settlements/withdraw-direct-bulk', $payload);
    }
}
