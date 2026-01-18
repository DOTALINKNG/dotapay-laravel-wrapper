<?php

namespace DotaPay\LaravelSdk\Data\Settlements;

use ArrayAccess;
use DotaPay\LaravelSdk\Data\Concerns\ArrayAccessible;

/**
 * Withdrawal response wrapper.
 *
 * @implements ArrayAccess<string, mixed>
 */
readonly class WithdrawalResponse implements ArrayAccess
{
    use ArrayAccessible;

    public function __construct(
        public ?Settlement $settlement,
        public ?string $message = null,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        // Handle 'data' wrapper
        $responseData = $data['data'] ?? $data;

        $settlement = null;
        if (isset($responseData['settlement']) && is_array($responseData['settlement'])) {
            $settlement = Settlement::fromArray($responseData['settlement']);
        } elseif (isset($responseData['id'])) {
            // Settlement data is at the root level
            $settlement = Settlement::fromArray($responseData);
        }

        return new self(
            settlement: $settlement,
            message: $data['message'] ?? null,
        );
    }
}
