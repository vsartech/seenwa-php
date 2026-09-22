<?php

declare(strict_types=1);

namespace Seen\Resources;

use Seen\SeenClient;

class CampaignsResource
{
    public function __construct(private readonly SeenClient $client)
    {
    }

    /**
     * @param array<int, string>|null $contactIds
     * @param array<int, array{phone: string, variables: array<string, string>}>|null $recipients
     */
    public function create(
        string $name,
        string $templateId,
        string $phoneNumberId,
        string $audienceType,
        ?string $segmentId = null,
        ?array $contactIds = null,
        ?array $recipients = null,
        ?string $csvFilename = null,
        ?string $scheduledAt = null
    ): mixed {
        return $this->client->request('POST', '/campaigns', [
            'name' => $name,
            'template_id' => $templateId,
            'phone_number_id' => $phoneNumberId,
            'audience_type' => $audienceType,
            'segment_id' => $segmentId,
            'contact_ids' => $contactIds,
            'recipients' => $recipients,
            'csv_filename' => $csvFilename,
            'scheduled_at' => $scheduledAt,
        ]);
    }

    public function list(?string $status = null, ?int $page = null, ?int $limit = null): mixed
    {
        return $this->client->request('GET', '/campaigns', null, [
            'status' => $status,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function get(string $id): mixed
    {
        return $this->client->request('GET', "/campaigns/{$id}");
    }

    public function send(string $id): mixed
    {
        return $this->client->request('POST', "/campaigns/{$id}/send");
    }

    public function analytics(string $id): mixed
    {
        return $this->client->request('GET', "/campaigns/{$id}/analytics");
    }

    public function delete(string $id): mixed
    {
        return $this->client->request('DELETE', "/campaigns/{$id}");
    }
}
