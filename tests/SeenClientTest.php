<?php

declare(strict_types=1);

namespace Seen\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Seen\SeenClient;

class SeenClientTest extends TestCase
{
    public function testConstructsWithApiKey(): void
    {
        $client = new SeenClient('test-key');
        $this->assertInstanceOf(SeenClient::class, $client);
    }

    public function testRejectsEmptyApiKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SeenClient('');
    }
}
