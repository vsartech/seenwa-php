<?php

declare(strict_types=1);

namespace Seen\Resources;

use Seen\SeenClient;

class ConversationsResource
{
    public function __construct(private readonly SeenClient $client)
    {
    }

    public function list(
        ?string $status = null,
        ?string $assignedTo = null,
        ?int $page = null,
        ?int $limit = null
    ): mixed {
        return $this->client->request('GET', '/conversations', null, [
            'status' => $status,
            'assigned_to' => $assignedTo,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function get(string $id): mixed
    {
        return $this->client->request('GET', "/conversations/{$id}");
    }

    public function sendMessage(string $id, string $message): mixed
    {
        return $this->client->request('POST', "/conversations/{$id}/send", ['message' => $message]);
    }

    public function resolve(string $id): mixed
    {
        return $this->client->request('POST', "/conversations/{$id}/resolve");
    }
}
