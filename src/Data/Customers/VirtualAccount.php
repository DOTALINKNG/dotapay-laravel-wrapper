<?php

namespace DotaPay\LaravelSdk\Data\Customers;

use ArrayAccess;
use DotaPay\LaravelSdk\Data\Concerns\ArrayAccessible;

/**
 * Virtual account entity.
 *
 * @implements ArrayAccess<string, mixed>
 */
readonly class VirtualAccount implements ArrayAccess
{
    use ArrayAccessible;

    public function __construct(
        public ?int $id,
        public ?string $code,
        public ?string $accountName,
        public ?string $accountNumber,
        public ?string $bankName,
        public ?string $bankCode,
        public ?string $status,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            code: $data['code'] ?? null,
            accountName: $data['account_name'] ?? null,
            accountNumber: $data['account_number'] ?? null,
            bankName: $data['bank_name'] ?? null,
            bankCode: $data['bank_code'] ?? null,
            status: $data['status'] ?? null,
        );
    }
}
