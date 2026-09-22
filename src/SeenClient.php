<?php

declare(strict_types=1);

namespace Seen;

use InvalidArgumentException;
use Seen\Resources\CallsResource;
use Seen\Resources\CampaignsResource;
use Seen\Resources\ContactsResource;
use Seen\Resources\ConversationsResource;
use Seen\Resources\MediaResource;
use Seen\Resources\MessagesResource;
use Seen\Resources\TemplatesResource;
use Seen\Resources\WebhooksResource;

/**
 * Client for the Seen WhatsApp Business API.
 *
 * Requires an API key generated from the Seen dashboard
 * (Settings > API Keys) - there is no other way to authenticate.
 */
class SeenClient
{
    private const DEFAULT_BASE_URL = 'https://api.seen.com/api/v1';

    private readonly string $apiKey;
    private readonly string $baseUrl;

    public readonly MessagesResource $messages;
    public readonly ConversationsResource $conversations;
    public readonly ContactsResource $contacts;
    public readonly TemplatesResource $templates;
    public readonly MediaResource $media;
    public readonly WebhooksResource $webhooks;
    public readonly CampaignsResource $campaigns;
    public readonly CallsResource $calls;

    public function __construct(string $apiKey, ?string $baseUrl = null)
    {
        if ($apiKey === '') {
            throw new InvalidArgumentException(
                'SeenClient requires an apiKey - generate one from the Seen '
                . 'dashboard under Settings > API Keys.'
            );
        }

        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl ?? self::DEFAULT_BASE_URL, '/');

        $this->messages = new MessagesResource($this);
        $this->conversations = new ConversationsResource($this);
        $this->contacts = new ContactsResource($this);
        $this->templates = new TemplatesResource($this);
        $this->media = new MediaResource($this);
        $this->webhooks = new WebhooksResource($this);
        $this->campaigns = new CampaignsResource($this);
        $this->calls = new CallsResource($this);
    }

    /**
     * Performs a request against the Seen API and returns the decoded
     * `data` field of the standard `{ success, data }` envelope.
     *
     * @param array<string, mixed>|null $body
     * @param array<string, scalar|null>|null $query
     */
    public function request(string $method, string $path, ?array $body = null, ?array $query = null): mixed
    {
        $url = $this->baseUrl . $path;

        if ($query !== null) {
            $cleanQuery = array_filter($query, static fn ($value) => $value !== null);
            if ($cleanQuery !== []) {
                $url .= '?' . http_build_query($cleanQuery);
            }
        }

        $ch = curl_init($url);
        $headers = [
            'Content-Type: application/json',
            'x-api-key: ' . $this->apiKey,
        ];

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_THROW_ON_ERROR));
        }

        $responseBody = curl_exec($ch);
        if ($responseBody === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new SeenApiException("Seen API request failed: {$error}", 0, null);
        }

        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($responseBody, true);

        if ($status < 200 || $status >= 300 || !is_array($json)) {
            throw new SeenApiException("Seen API request failed with status {$status}", $status, $json);
        }

        return $json['data'] ?? null;
    }
}
