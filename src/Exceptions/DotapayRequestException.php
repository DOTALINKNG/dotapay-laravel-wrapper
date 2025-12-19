<?php

namespace DotaPay\LaravelSdk\Exceptions;

use RuntimeException;

class DotapayRequestException extends RuntimeException
{
    public function __construct(
        public readonly int $status,
        public readonly ?array $response,
        string $message = 'DotaPay request failed',
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $status, $previous);
    }
}
