<?php

declare(strict_types=1);

namespace Seen\Resources;

use Seen\SeenClient;

/**
 * WhatsApp Calling (VoIP) - a separate Meta approval from base messaging.
 * Requires a Cloud-API-native phone number, calling explicitly enabled on
 * that number, and either 2,000+ daily unique message recipients or Tech
 * Partner sandbox access. Seen relays SDP offers/answers through Meta;
 * actual WebRTC media negotiation happens in your own client.
 */
class CallsResource
{
    public function __construct(private readonly SeenClient $client)
    {
    }

    public function list(?string $status = null, ?int $page = null, ?int $limit = null): mixed
    {
        return $this->client->request('GET', '/calls', null, [
            'status' => $status,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function get(string $id): mixed
    {
        return $this->client->request('GET', "/calls/{$id}");
    }

    /**
     * @param array{type: string, sdp: string} $sdpOffer
     */
    public function initiate(string $phoneNumberId, string $to, array $sdpOffer): mixed
    {
        return $this->client->request('POST', '/calls', [
            'phone_number_id' => $phoneNumberId,
            'to' => $to,
            'sdp_offer' => $sdpOffer,
        ]);
    }

    /**
     * @param array{type: string, sdp: string} $sdpAnswer
     */
    public function accept(string $id, array $sdpAnswer): mixed
    {
        return $this->client->request('POST', "/calls/{$id}/accept", ['sdp_answer' => $sdpAnswer]);
    }

    public function reject(string $id): mixed
    {
        return $this->client->request('POST', "/calls/{$id}/reject");
    }

    public function terminate(string $id): mixed
    {
        return $this->client->request('POST', "/calls/{$id}/terminate");
    }
}
