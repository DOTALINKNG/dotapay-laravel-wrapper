<?php

namespace DotaPay\LaravelSdk\Data\Customers;

use ArrayAccess;
use DotaPay\LaravelSdk\Data\Concerns\ArrayAccessible;

/**
 * Customer balance response.
 *
 * @implements ArrayAccess<string, mixed>
 */
readonly class CustomerBalance implements ArrayAccess
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
        // Handle 'data' wrapper
        $balanceData = $data['data'] ?? $data;

        return new self(
            name: $balanceData['name'] ?? null,
            slug: $balanceData['slug'] ?? null,
            balance: (float) ($balanceData['balance'] ?? 0),
            balanceKobo: (int) ($balanceData['balanceKobo'] ?? $balanceData['balance_kobo'] ?? 0),
        );
    }
}
