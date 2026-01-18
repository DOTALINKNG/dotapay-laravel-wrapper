<?php

namespace DotaPay\LaravelSdk\Data\Payment;

use ArrayAccess;
use DotaPay\LaravelSdk\Data\Concerns\ArrayAccessible;

/**
 * Wallet entity.
 *
 * @implements ArrayAccess<string, mixed>
 */
readonly class Wallet implements ArrayAccess
{
    use ArrayAccessible;

    public function __construct(
        public ?string $name,
        public ?string $slug,
        public float $balance,
        public int $balanceKobo,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            slug: $data['slug'] ?? null,
            balance: (float) ($data['balance'] ?? 0),
            balanceKobo: (int) ($data['balanceKobo'] ?? $data['balance_kobo'] ?? 0),
        );
    }
}
