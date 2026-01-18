<?php

namespace DotaPay\LaravelSdk\Data\Customers;

use ArrayAccess;
use DotaPay\LaravelSdk\Data\Common\PaginationMeta;
use DotaPay\LaravelSdk\Data\Concerns\ArrayAccessible;

/**
 * Paginated collection of transactions.
 *
 * @implements ArrayAccess<string, mixed>
 */
readonly class TransactionCollection implements ArrayAccess
{
    use ArrayAccessible;

    /**
     * @param array<Transaction> $items
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
                    $items[] = Transaction::fromArray($item);
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
