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
     * Create customer, but if the reference already exists, return that customer via show(reference).
     *
     * This is useful for idempotent onboarding.
     */
    public function createOrGetByReference(array $payload): array
    {
        $reference = $payload['reference'] ?? null;
        if (!is_string($reference) || $reference === '') {
            // Let create() throw a validation error from the API
            return $this->create($payload);
        }

        try {
            return $this->create($payload);
        } catch (DotapayRequestException $e) {
            $msg = strtolower($e->getMessage());

            $looksLikeDuplicateRef = (
                $e->status === 422 || $e->status === 400
            ) && (
                str_contains($msg, 'reference') && str_contains($msg, 'exist')
            );

            if (!$looksLikeDuplicateRef) {
                throw $e;
            }

            // Pull existing customer using the same identifier your API supports.
            return $this->show($reference);
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
