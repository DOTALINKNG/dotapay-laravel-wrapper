<?php

namespace DotaPay\LaravelSdk\Data\Settlements;

use ArrayAccess;
use DotaPay\LaravelSdk\Data\Concerns\ArrayAccessible;

/**
 * Settlement entity.
 *
 * @implements ArrayAccess<string, mixed>
 */
readonly class Settlement implements ArrayAccess
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
        public ?int $walletId,
        public float $amount,
        public int $amountKobo,
        public ?string $bankName,
        public ?string $bankCode,
        public ?string $accountNumber,
        public ?string $accountName,
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
            walletId: isset($data['wallet_id']) ? (int) $data['wallet_id'] : null,
            amount: (float) ($data['amount'] ?? 0),
            amountKobo: (int) ($data['amount_kobo'] ?? $data['amountKobo'] ?? 0),
            bankName: $data['bank_name'] ?? null,
            bankCode: $data['bank_code'] ?? null,
            accountNumber: $data['account_number'] ?? null,
            accountName: $data['account_name'] ?? null,
            meta: isset($data['meta']) && is_array($data['meta']) ? $data['meta'] : null,
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null,
        );
    }
}
