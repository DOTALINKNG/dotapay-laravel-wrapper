<?php

namespace DotaPay\LaravelSdk\Data\Customers;

use ArrayAccess;
use DotaPay\LaravelSdk\Data\Concerns\ArrayAccessible;

/**
 * Wallet transaction entity.
 *
 * @implements ArrayAccess<string, mixed>
 */
readonly class Transaction implements ArrayAccess
{
    use ArrayAccessible;

    /**
     * @param array<string, mixed>|null $meta
     */
    public function __construct(
        public ?int $id,
        public ?string $type,
        public float $amount,
        public bool $confirmed,
        public ?array $meta,
        public ?string $uuid,
        public ?string $createdAt,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            type: $data['type'] ?? null,
            amount: (float) ($data['amount'] ?? 0),
            confirmed: (bool) ($data['confirmed'] ?? false),
            meta: isset($data['meta']) && is_array($data['meta']) ? $data['meta'] : null,
            uuid: $data['uuid'] ?? null,
            createdAt: $data['created_at'] ?? null,
        );
    }
}
