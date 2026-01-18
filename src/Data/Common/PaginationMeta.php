<?php

namespace DotaPay\LaravelSdk\Data\Common;

use ArrayAccess;
use DotaPay\LaravelSdk\Data\Concerns\ArrayAccessible;

/**
 * Pagination metadata from API responses.
 *
 * @implements ArrayAccess<string, mixed>
 */
readonly class PaginationMeta implements ArrayAccess
{
    use ArrayAccessible;

    public function __construct(
        public int $currentPage,
        public int $lastPage,
        public int $perPage,
        public int $total,
        public ?string $path = null,
        public ?int $from = null,
        public ?int $to = null,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            currentPage: (int) ($data['current_page'] ?? 1),
            lastPage: (int) ($data['last_page'] ?? 1),
            perPage: (int) ($data['per_page'] ?? 15),
            total: (int) ($data['total'] ?? 0),
            path: $data['path'] ?? null,
            from: isset($data['from']) ? (int) $data['from'] : null,
            to: isset($data['to']) ? (int) $data['to'] : null,
        );
    }
}
