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
    /** Error codes the API uses when a request is blocked by the account's plan or billing state. */
    public const PLAN_ERROR_CODES = [
        'feature_not_in_plan',
        'limit_reached',
        'subscription_locked',
        'ai_insufficient_funds',
    ];

    public readonly int $status;
    public readonly mixed $body;

    public function __construct(string $message, int $status, mixed $body = null)
    {
        parent::__construct($message);
        $this->status = $status;
        $this->body = $body;
    }

    /** Machine-readable error code from the response body, when the API sent one. */
    public function code(): ?string
    {
        if (is_array($this->body) && isset($this->body['code']) && is_string($this->body['code'])) {
            return $this->body['code'];
        }
        return null;
    }

    /**
     * True when the request was refused because of the account's plan, a plan limit or the billing
     * state (HTTP 402), rather than because the request was wrong. See code() for which one.
     */
    public function isPlanError(): bool
    {
        return $this->status === 402 && in_array($this->code(), self::PLAN_ERROR_CODES, true);
    }
}
