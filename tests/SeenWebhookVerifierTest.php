<?php

declare(strict_types=1);

namespace Seen\Tests;

use PHPUnit\Framework\TestCase;
use Seen\SeenWebhookVerifier;

class SeenWebhookVerifierTest extends TestCase
{
    private const SECRET = 'whsec_test';
    private const BODY = '{"event":"message.received"}';

    public function testAcceptsCorrectlySignedPayload(): void
    {
        $signature = 'sha256=' . hash_hmac('sha256', self::BODY, self::SECRET);

        $this->assertTrue(SeenWebhookVerifier::verify(self::BODY, $signature, self::SECRET));
    }

    public function testRejectsTamperedPayload(): void
    {
        $signature = 'sha256=' . hash_hmac('sha256', self::BODY, self::SECRET);

        $this->assertFalse(
            SeenWebhookVerifier::verify('{"event":"message.deleted"}', $signature, self::SECRET)
        );
    }

    public function testRejectsMissingOrMalformedHeader(): void
    {
        $this->assertFalse(SeenWebhookVerifier::verify('{}', null, 's'));
        $this->assertFalse(SeenWebhookVerifier::verify('{}', 'not-a-signature', 's'));
    }
}
