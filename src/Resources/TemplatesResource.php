<?php

declare(strict_types=1);

namespace Seen\Resources;

use Seen\SeenClient;

class TemplatesResource
{
    public function __construct(private readonly SeenClient $client)
    {
    }

    /**
     * @param array<int, mixed> $components
     */
    public function create(string $name, string $category, array $components, string $language = 'en'): mixed
    {
        return $this->client->request('POST', '/templates', [
            'name' => $name,
            'category' => $category,
            'language' => $language,
            'components' => $components,
        ]);
    }

    public function list(?string $status = null, ?string $category = null, ?int $page = null, ?int $limit = null): mixed
    {
        return $this->client->request('GET', '/templates', null, [
            'status' => $status,
            'category' => $category,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function get(string $id): mixed
    {
        return $this->client->request('GET', "/templates/{$id}");
    }

    /**
     * The {{1}}, {{2}}, ... placeholders this template needs, and the CSV
     * column names a campaign audience upload should use.
     */
    public function getVariables(string $id): mixed
    {
        return $this->client->request('GET', "/templates/{$id}/variables");
    }
}
