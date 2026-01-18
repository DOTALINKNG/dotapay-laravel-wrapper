<?php

namespace DotaPay\LaravelSdk\Http\Clients;

use DotaPay\LaravelSdk\Exceptions\DotapayRequestException;

class CustomersClient extends BaseClient
{
    /**
     * List all customers.
     *
     * @param array{
     *     page?: int,
     *     per_page?: int,
     *     search?: string,
     *     type?: 'wallet'|'collection',
     *     status?: string,
     * } $query
     * @return array{
     *     data: array<array{
     *         id: int,
     *         code: string,
     *         reference: string,
     *         first_name: string,
     *         last_name: string,
     *         email: string,
     *         phone: string|null,
     *         type: string,
     *         status: string,
     *         wallet?: array{slug: string, balance: float, balance_kobo: int},
     *         virtual_accounts?: array<array{bank_name: string, account_number: string, account_name: string}>,
     *     }>,
     *     meta: array{current_page: int, last_page: int, per_page: int, total: int},
     * }
     */
    public function index(array $query = []): array
    {
        return $this->get('customers', $query);
    }

    /**
     * Get a single customer by identifier (ID, code, reference, or email).
     *
     * @param array{
     *     include?: string,
     * } $query
     * @return array{
     *     data: array{
     *         id: int,
     *         code: string,
     *         reference: string,
     *         first_name: string,
     *         last_name: string,
     *         email: string,
     *         phone: string|null,
     *         type: string,
     *         status: string,
     *         wallet?: array{slug: string, balance: float, balance_kobo: int},
     *         virtual_accounts?: array<array{bank_name: string, account_number: string, account_name: string}>,
     *     },
     * }
     */
    public function show(string|int $identifier, array $query = []): array
    {
        return $this->get("customers/{$identifier}", $query);
    }

    /**
     * Create an individual customer (fails if email/reference already exists).
     *
     * @param array{
     *     first_name: string,
     *     last_name: string,
     *     bvn: string,
     *     dob: string,
     *     email: string,
     *     reference: string,
     *     type: 'wallet'|'collection',
     *     nin?: string,
     *     phone?: string,
     *     collection_wallet?: string,
     * } $payload
     * @return array{
     *     data: array{
     *         id: int,
     *         code: string,
     *         reference: string,
     *         first_name: string,
     *         last_name: string,
     *         email: string,
     *         phone: string|null,
     *         type: string,
     *         status: string,
     *         wallet?: array{slug: string, balance: float, balance_kobo: int},
     *         virtual_accounts?: array<array{bank_name: string, account_number: string, account_name: string}>,
     *     },
     * }
     */
    public function create(array $payload): array
    {
        return $this->post('customers', $payload);
    }

    /**
     * Create an individual customer, updates if customer already exists.
     *
     * @param array{
     *     first_name: string,
     *     last_name: string,
     *     bvn: string,
     *     dob: string,
     *     email: string,
     *     reference: string,
     *     type: 'wallet'|'collection',
     *     nin?: string,
     *     phone?: string,
     *     collection_wallet?: string,
     * } $payload
     * @return array{
     *     data: array{
     *         id: int,
     *         code: string,
     *         reference: string,
     *         first_name: string,
     *         last_name: string,
     *         email: string,
     *         phone: string|null,
     *         type: string,
     *         status: string,
     *         wallet?: array{slug: string, balance: float, balance_kobo: int},
     *         virtual_accounts?: array<array{bank_name: string, account_number: string, account_name: string}>,
     *     },
     * }
     */
    public function createOrUpdate(array $payload): array
    {
        return $this->post('customers/storeorupdate', $payload);
    }

    /**
     * Create a corporate customer (fails if email/reference already exists).
     *
     * @param array{
     *     company_name: string,
     *     rc_number: string,
     *     email: string,
     *     reference: string,
     *     type: 'wallet'|'collection',
     *     phone?: string,
     *     collection_wallet?: string,
     * } $payload
     * @return array{
     *     data: array{
     *         id: int,
     *         code: string,
     *         reference: string,
     *         first_name: string,
     *         last_name: string,
     *         email: string,
     *         phone: string|null,
     *         type: string,
     *         status: string,
     *         wallet?: array{slug: string, balance: float, balance_kobo: int},
     *         virtual_accounts?: array<array{bank_name: string, account_number: string, account_name: string}>,
     *     },
     * }
     */
    public function createCorporate(array $payload): array
    {
        return $this->post('customers/corporate', $payload);
    }

    /**
     * Create customer, but if customer already exists (duplicate email OR reference),
     * return the existing customer via show(identifier).
     *
     * show() accepts either reference or email.
     *
     * @param array{
     *     first_name: string,
     *     last_name: string,
     *     bvn: string,
     *     dob: string,
     *     email: string,
     *     reference: string,
     *     type: 'wallet'|'collection',
     *     nin?: string,
     *     phone?: string,
     *     collection_wallet?: string,
     * } $payload
     * @return array{
     *     data: array{
     *         id: int,
     *         code: string,
     *         reference: string,
     *         first_name: string,
     *         last_name: string,
     *         email: string,
     *         phone: string|null,
     *         type: string,
     *         status: string,
     *         wallet?: array{slug: string, balance: float, balance_kobo: int},
     *         virtual_accounts?: array<array{bank_name: string, account_number: string, account_name: string}>,
     *     },
     * }
     */
    public function createOrGetByReference(array $payload): array
    {
        $reference = isset($payload['reference']) ? trim((string) $payload['reference']) : '';
        $email     = isset($payload['email']) ? trim((string) $payload['email']) : '';

        // prefer email when the API complains about email, otherwise reference
        try {
            return $this->createOrUpdate($payload);
        } catch (DotapayRequestException $e) {
            $msg = strtolower((string) $e->getMessage());

            $looksLikeDuplicate = in_array((int) $e->status, [400, 409, 422, 500], true)
                && (
                    str_contains($msg, 'already exists') ||
                    str_contains($msg, 'already exist') ||
                    str_contains($msg, 'duplicate') ||
                    (str_contains($msg, 'exist') && (str_contains($msg, 'email') || str_contains($msg, 'reference')))
                );

            if (! $looksLikeDuplicate) {
                throw $e;
            }

            // If message points to email, try email first
            $preferEmail = str_contains($msg, 'email');

            $candidates = array_values(array_filter([
                $preferEmail ? $email : $reference,
                $preferEmail ? $reference : $email,
            ], fn($v) => is_string($v) && $v !== ''));

            if (empty($candidates)) {
                // nothing to recover with, bubble up original
                throw $e;
            }

            // Try show() with whichever identifier works (email or reference)
            $last = null;
            foreach ($candidates as $id) {
                try {
                    return $this->show($id);
                } catch (DotapayRequestException $ex) {
                    $last = $ex;
                }
            }

            // If all recovery attempts failed, throw the last error (or original)
            throw $last ?? $e;
        }
    }

    /**
     * Get customer balance.
     *
     * @return array{
     *     data: array{
     *         name: string|null,
     *         slug: string|null,
     *         balance: float,
     *         balance_kobo: int,
     *     },
     * }
     */
    public function balance(string|int $identifier): array
    {
        return $this->get("customers/{$identifier}/balance");
    }

    /**
     * Get customer wallet transactions.
     *
     * @param array{
     *     page?: int,
     *     per_page?: int,
     *     type?: string,
     * } $query
     * @return array{
     *     data: array<array{
     *         id: int,
     *         reference: string,
     *         type: string,
     *         amount: float,
     *         amount_kobo: int,
     *         status: string,
     *         narration: string|null,
     *         created_at: string,
     *     }>,
     *     meta: array{current_page: int, last_page: int, per_page: int, total: int},
     * }
     */
    public function transactions(string|int $identifier, array $query = []): array
    {
        return $this->get("customers/{$identifier}/transactions", $query);
    }

    /**
     * Get customer virtual accounts.
     *
     * @return array{
     *     data: array<array{
     *         bank_name: string,
     *         bank_code: string,
     *         account_number: string,
     *         account_name: string,
     *     }>,
     * }
     */
    public function virtualAccounts(string|int $identifier): array
    {
        return $this->get("customers/{$identifier}/virtual-accounts");
    }
}
