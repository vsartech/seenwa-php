<?php

declare(strict_types=1);

namespace Seen\Resources;

use Seen\SeenClient;

class ContactsResource
{
    public function __construct(private readonly SeenClient $client)
    {
    }

    public function list(
        ?string $search = null,
        ?bool $optedIn = null,
        ?int $page = null,
        ?int $limit = null
    ): mixed {
        return $this->client->request('GET', '/contacts', null, [
            'search' => $search,
            'opted_in' => $optedIn,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function get(string $id): mixed
    {
        return $this->client->request('GET', "/contacts/{$id}");
    }

    /**
     * @param array<int, string>|null $tags
     */
    public function create(
        string $phone,
        ?string $name = null,
        ?string $email = null,
        ?string $language = null,
        ?array $tags = null,
        bool $optedIn = false
    ): mixed {
        return $this->client->request('POST', '/contacts', [
            'phone' => $phone,
            'name' => $name,
            'email' => $email,
            'language' => $language,
            'tags' => $tags,
            'opted_in' => $optedIn,
        ]);
    }

    public function update(string $id, ?string $name = null, ?string $email = null): mixed
    {
        return $this->client->request('PUT', "/contacts/{$id}", [
            'name' => $name,
            'email' => $email,
        ]);
    }

    public function delete(string $id): mixed
    {
        return $this->client->request('DELETE', "/contacts/{$id}");
    }

    /**
     * @param array<int, array<string, mixed>> $contacts
     */
    public function import(array $contacts): mixed
    {
        return $this->client->request('POST', '/contacts/import', ['contacts' => $contacts]);
    }

    public function optOut(string $phone): mixed
    {
        return $this->client->request('POST', '/contacts/opt-out/' . rawurlencode($phone));
    }
}
