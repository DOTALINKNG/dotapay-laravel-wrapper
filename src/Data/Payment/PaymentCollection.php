<?php

namespace DotaPay\LaravelSdk\Data\Payment;

use ArrayAccess;
use DotaPay\LaravelSdk\Data\Common\PaginationMeta;
use DotaPay\LaravelSdk\Data\Concerns\ArrayAccessible;

/**
 * Paginated collection of payment transactions.
 *
 * @implements ArrayAccess<string, mixed>
 */
readonly class PaymentCollection implements ArrayAccess
{
    use ArrayAccessible;

    /**
     * @param array<PaymentTransaction> $items
     */
    public function __construct(
        public array $items,
        public PaginationMeta $meta,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $items = [];
        $rawItems = $data['data'] ?? [];

        if (is_array($rawItems)) {
            foreach ($rawItems as $item) {
                if (is_array($item)) {
                    $items[] = PaymentTransaction::fromArray($item);
                }
            }
        }

        // Extract meta from top-level or nested 'meta' key
        $metaData = $data['meta'] ?? $data;

        return new self(
            items: $items,
            meta: PaginationMeta::fromArray($metaData),
        );
    }
}
