<?php

namespace DotaPay\LaravelSdk\Data\Customers;

use ArrayAccess;
use DotaPay\LaravelSdk\Data\Concerns\ArrayAccessible;
use DotaPay\LaravelSdk\Data\Payment\Wallet;

/**
 * Customer entity.
 *
 * @implements ArrayAccess<string, mixed>
 */
readonly class Customer implements ArrayAccess
{
    use ArrayAccessible;

    /**
     * @param array<VirtualAccount> $virtualAccounts
     */
    public function __construct(
        public ?int $id,
        public ?string $code,
        public ?string $reference,
        public ?string $firstName,
        public ?string $lastName,
        public ?string $email,
        public ?string $phone,
        public ?string $type,
        public ?string $status,
        public ?Wallet $wallet = null,
        public array $virtualAccounts = [],
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        // Handle both 'data' wrapper and direct data
        $customerData = $data['data'] ?? $data;

        $virtualAccounts = [];
        if (isset($customerData['virtual_accounts']) && is_array($customerData['virtual_accounts'])) {
            $virtualAccounts = array_map(
                fn (array $va) => VirtualAccount::fromArray($va),
                $customerData['virtual_accounts']
            );
        }

        $wallet = null;
        if (isset($customerData['wallet']) && is_array($customerData['wallet'])) {
            $wallet = Wallet::fromArray($customerData['wallet']);
        }

        return new self(
            id: isset($customerData['id']) ? (int) $customerData['id'] : null,
            code: $customerData['code'] ?? null,
            reference: $customerData['reference'] ?? null,
            firstName: $customerData['first_name'] ?? null,
            lastName: $customerData['last_name'] ?? null,
            email: $customerData['email'] ?? null,
            phone: $customerData['phone'] ?? null,
            type: $customerData['type'] ?? null,
            status: $customerData['status'] ?? null,
            wallet: $wallet,
            virtualAccounts: $virtualAccounts,
        );
    }
}
