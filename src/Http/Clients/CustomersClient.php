<?php

namespace DotaPay\LaravelSdk\Http\Clients;

use DotaPay\LaravelSdk\Exceptions\DotapayRequestException;

class CustomersClient extends BaseClient
{
    public function index(array $query = []): array
    {
        return $this->get('customers', $query);
    }

    public function show(string|int $identifier, array $query = []): array
    {
        return $this->get("customers/{$identifier}", $query);
    }

    /**
     * Create an individual customer (fails if email/reference already exists).
     */
    public function create(array $payload): array
    {
        return $this->post('customers', $payload);
    }

    /**
     * Create a corporate customer (fails if email/reference already exists).
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
     */
    public function createOrGet(array $payload): array
    {
        $reference = isset($payload['reference']) ? trim((string) $payload['reference']) : '';
        $email     = isset($payload['email']) ? trim((string) $payload['email']) : '';

        // prefer email when the API complains about email, otherwise reference
        try {
            return $this->create($payload);
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


    public function balance(string|int $identifier): array
    {
        return $this->get("customers/{$identifier}/balance");
    }

    public function transactions(string|int $identifier, array $query = []): array
    {
        return $this->get("customers/{$identifier}/transactions", $query);
    }

    public function virtualAccounts(string|int $identifier): array
    {
        return $this->get("customers/{$identifier}/virtual-accounts");
    }
}
