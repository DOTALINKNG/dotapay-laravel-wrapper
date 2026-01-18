<?php

namespace DotaPay\LaravelSdk\Http\Clients;

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
     * @return array{
     *     data: array{
     *         transaction: array{
     *             id: int,
     *             reference: string,
     *             amount: float,
     *             amount_kobo: int,
     *             status: string,
     *             type: string,
     *             narration: string|null,
     *             created_at: string,
     *         },
     *         wallet?: array{slug: string, balance: float, balance_kobo: int},
     *         wallet_transactions?: array<array{id: int, reference: string, amount: float, status: string}>,
     *     },
     * }
     */
    public function request(array $payload): array
    {
        return $this->post('payment/request', $payload);
    }

    /**
     * Verify a payment using OTP or PIN.
     *
     * @param array{
     *     reference: string,
     *     otp?: string,
     *     pin?: string,
     * } $payload
     * @return array{
     *     data: array{
     *         transaction: array{
     *             id: int,
     *             reference: string,
     *             amount: float,
     *             amount_kobo: int,
     *             status: string,
     *             type: string,
     *             narration: string|null,
     *             created_at: string,
     *         },
     *         wallet?: array{slug: string, balance: float, balance_kobo: int},
     *         wallet_transactions?: array<array{id: int, reference: string, amount: float, status: string}>,
     *     },
     * }
     */
    public function verify(array $payload): array
    {
        return $this->post('payment/verify', $payload);
    }

    /**
     * Get the status of a payment by reference.
     *
     * @return array{
     *     data: array{
     *         transaction: array{
     *             id: int,
     *             reference: string,
     *             amount: float,
     *             amount_kobo: int,
     *             status: string,
     *             type: string,
     *             narration: string|null,
     *             created_at: string,
     *         },
     *         wallet?: array{slug: string, balance: float, balance_kobo: int},
     *         wallet_transactions?: array<array{id: int, reference: string, amount: float, status: string}>,
     *     },
     * }
     */
    public function status(string $reference): array
    {
        return $this->get("payment/status/{$reference}");
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
     * @return array{
     *     data: array<array{
     *         id: int,
     *         reference: string,
     *         amount: float,
     *         amount_kobo: int,
     *         status: string,
     *         type: string,
     *         narration: string|null,
     *         created_at: string,
     *     }>,
     *     meta: array{current_page: int, last_page: int, per_page: int, total: int},
     * }
     */
    public function list(array $query = []): array
    {
        return $this->get('payment/list', $query);
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
     * @return array{
     *     data: array{
     *         transaction: array{
     *             id: int,
     *             reference: string,
     *             amount: float,
     *             amount_kobo: int,
     *             status: string,
     *             type: string,
     *             narration: string|null,
     *             created_at: string,
     *         },
     *         wallet?: array{slug: string, balance: float, balance_kobo: int},
     *         wallet_transactions?: array<array{id: int, reference: string, amount: float, status: string}>,
     *     },
     * }
     */
    public function initCustomer(array $payload): array
    {
        return $this->post('payment/init-customer', $payload);
    }

    /**
     * Verify a customer payment.
     *
     * @param array{
     *     reference: string,
     *     otp?: string,
     *     pin?: string,
     * } $payload
     * @return array{
     *     data: array{
     *         transaction: array{
     *             id: int,
     *             reference: string,
     *             amount: float,
     *             amount_kobo: int,
     *             status: string,
     *             type: string,
     *             narration: string|null,
     *             created_at: string,
     *         },
     *         wallet?: array{slug: string, balance: float, balance_kobo: int},
     *         wallet_transactions?: array<array{id: int, reference: string, amount: float, status: string}>,
     *     },
     * }
     */
    public function verifyCustomer(array $payload): array
    {
        return $this->post('payment/verify-customer', $payload);
    }
}
