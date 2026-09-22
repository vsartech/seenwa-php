<?php

declare(strict_types=1);

namespace Seen;

/**
 * Verifies the `X-Webhook-Signature` header Seen sends with every outbound
 * client webhook delivery. The header is `sha256=<hmac hex digest>`, HMAC'd
 * over the exact raw request body bytes using the webhook's `secret`
 * (shown once, at creation, by WebhooksResource::create()).
 *
 * Always verify against the raw request body (e.g. PHP's `php://input`)
 * before it has been decoded/re-encoded - re-serializing a parsed JSON
 * array will not reliably reproduce the exact bytes that were signed.
 */
class SeenWebhookVerifier
{
    public static function verify(string $rawBody, ?string $signatureHeader, string $secret): bool
    {
        if ($signatureHeader === null || !str_starts_with($signatureHeader, 'sha256=')) {
            return false;
        }

        $expected = substr($signatureHeader, strlen('sha256='));
        $computed = hash_hmac('sha256', $rawBody, $secret);

        return hash_equals($computed, $expected);
    }
}
