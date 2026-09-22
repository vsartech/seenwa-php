<?php

declare(strict_types=1);

namespace Seen\Resources;

use Seen\SeenClient;

class MediaResource
{
    public function __construct(private readonly SeenClient $client)
    {
    }

    public function list(?int $page = null, ?int $limit = null): mixed
    {
        return $this->client->request('GET', '/media', null, [
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function upload(string $file, ?string $fileType = null, ?string $fileName = null): mixed
    {
        return $this->client->request('POST', '/media/upload', [
            'file' => $file,
            'file_type' => $fileType,
            'file_name' => $fileName,
        ]);
    }

    public function delete(string $id): mixed
    {
        return $this->client->request('DELETE', "/media/{$id}");
    }
}
