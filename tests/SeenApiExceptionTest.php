<?php

declare(strict_types=1);

namespace Seen\Tests;

use PHPUnit\Framework\TestCase;
use Seen\SeenApiException;

class SeenApiExceptionTest extends TestCase
{
    public function testPlanBlockedRequestExposesCode(): void
    {
        $e = new SeenApiException('Not in your plan', 402, ['success' => false, 'code' => 'feature_not_in_plan', 'feature' => 'api']);
        $this->assertSame('feature_not_in_plan', $e->code());
        $this->assertTrue($e->isPlanError());
        $this->assertSame('api', $e->body['feature']);
    }

    public function testOrdinaryErrorsAreNotPlanErrors(): void
    {
        $this->assertFalse((new SeenApiException('Payment needed', 402, ['error' => 'x']))->isPlanError());
        $this->assertNull((new SeenApiException('Payment needed', 402, ['error' => 'x']))->code());
        $this->assertFalse((new SeenApiException('bad', 400, ['code' => 'limit_reached']))->isPlanError());
        $this->assertFalse((new SeenApiException('boom', 502, null))->isPlanError());
    }
}
