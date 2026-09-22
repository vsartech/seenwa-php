<?php

declare(strict_types=1);

namespace Seen\Resources;

use Seen\SeenClient;

class MessagesResource
{
    public function __construct(private readonly SeenClient $client)
    {
    }

    public function sendText(string $to, string $phoneNumberId, string $message): mixed
    {
        return $this->client->request('POST', '/messages/text', [
            'to' => $to,
            'phone_number_id' => $phoneNumberId,
            'message' => $message,
        ]);
    }

    /**
     * @param array<int, mixed>|null $components
     */
    public function sendTemplate(
        string $to,
        string $phoneNumberId,
        string $templateName,
        string $languageCode = 'en',
        ?array $components = null
    ): mixed {
        return $this->client->request('POST', '/messages/template', [
            'to' => $to,
            'phone_number_id' => $phoneNumberId,
            'template_name' => $templateName,
            'language_code' => $languageCode,
            'components' => $components ?? [],
        ]);
    }

    public function sendMedia(
        string $to,
        string $phoneNumberId,
        string $type,
        ?string $mediaId = null,
        ?string $link = null,
        ?string $caption = null,
        ?string $filename = null
    ): mixed {
        return $this->client->request('POST', '/messages/media', [
            'to' => $to,
            'phone_number_id' => $phoneNumberId,
            'type' => $type,
            'media_id' => $mediaId,
            'link' => $link,
            'caption' => $caption,
            'filename' => $filename,
        ]);
    }

    public function list(
        ?string $contact = null,
        ?string $conversation = null,
        ?string $status = null,
        ?string $type = null,
        ?int $page = null,
        ?int $limit = null
    ): mixed {
        return $this->client->request('GET', '/messages', null, [
            'contact' => $contact,
            'conversation' => $conversation,
            'status' => $status,
            'type' => $type,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function get(string $id): mixed
    {
        return $this->client->request('GET', "/messages/{$id}");
    }
}
