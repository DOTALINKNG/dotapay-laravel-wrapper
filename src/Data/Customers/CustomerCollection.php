<?php

namespace DotaPay\LaravelSdk\Data\Customers;

use ArrayAccess;
use DotaPay\LaravelSdk\Data\Common\PaginationMeta;
use DotaPay\LaravelSdk\Data\Concerns\ArrayAccessible;

/**
 * Paginated collection of customers.
 *
 * @implements ArrayAccess<string, mixed>
 */
readonly class CustomerCollection implements ArrayAccess
{
    use ArrayAccessible;

    /**
     * @param array<Customer> $items
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
                    $items[] = Customer::fromArray($item);
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
