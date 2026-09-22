<?php

declare(strict_types=1);

namespace Seen;

use RuntimeException;

/**
 * Thrown when a request to the Seen API fails (non-2xx response or an
 * unparseable body).
 */
class SeenApiException extends RuntimeException
{
    public readonly int $status;
    public readonly mixed $body;

    public function __construct(string $message, int $status, mixed $body = null)
    {
        parent::__construct($message);
        $this->status = $status;
        $this->body = $body;
    }
}
