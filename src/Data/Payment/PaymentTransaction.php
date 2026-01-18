<?php

namespace DotaPay\LaravelSdk\Data\Payment;

use ArrayAccess;
use DotaPay\LaravelSdk\Data\Concerns\ArrayAccessible;

/**
 * Payment transaction entity.
 *
 * @implements ArrayAccess<string, mixed>
 */
readonly class PaymentTransaction implements ArrayAccess
{
    use ArrayAccessible;

    /**
     * @param array<string, mixed>|null $meta
     */
    public function __construct(
        public ?int $id,
        public ?string $code,
        public ?string $reference,
        public ?string $type,
        public ?string $status,
        public float $amount,
        public int $amountKobo,
        public ?string $currency,
        public ?string $description,
        public ?string $channel,
        public ?array $meta,
        public ?string $createdAt,
        public ?string $updatedAt,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            code: $data['code'] ?? null,
            reference: $data['reference'] ?? null,
            type: $data['type'] ?? null,
            status: $data['status'] ?? null,
            amount: (float) ($data['amount'] ?? 0),
            amountKobo: (int) ($data['amount_kobo'] ?? $data['amountKobo'] ?? 0),
            currency: $data['currency'] ?? null,
            description: $data['description'] ?? null,
            channel: $data['channel'] ?? null,
            meta: isset($data['meta']) && is_array($data['meta']) ? $data['meta'] : null,
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null,
        );
    }
}
