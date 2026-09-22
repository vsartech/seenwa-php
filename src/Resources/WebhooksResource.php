<?php

declare(strict_types=1);

namespace Seen\Resources;

use Seen\SeenClient;

class WebhooksResource
{
    public function __construct(private readonly SeenClient $client)
    {
    }

    public function list(): mixed
    {
        return $this->client->request('GET', '/webhooks');
    }

    /**
     * @param array<int, string> $events
     */
    public function create(string $url, array $events): mixed
    {
        return $this->client->request('POST', '/webhooks', ['url' => $url, 'events' => $events]);
    }

    /**
     * @param array<int, string>|null $events
     */
    public function update(string $id, ?string $url = null, ?array $events = null, ?bool $isActive = null): mixed
    {
        return $this->client->request('PATCH', "/webhooks/{$id}", [
            'url' => $url,
            'events' => $events,
            'is_active' => $isActive,
        ]);
    }

    public function delete(string $id): mixed
    {
        return $this->client->request('DELETE', "/webhooks/{$id}");
    }

    public function test(string $id): mixed
    {
        return $this->client->request('POST', "/webhooks/{$id}/test");
    }
}
