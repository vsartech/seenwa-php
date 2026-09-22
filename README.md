# seenwa (PHP)

PHP SDK for the Seen WhatsApp Business API.

## Install

```bash
composer require vsartech/seenwa
```

## Usage

```php
use Seen\SeenClient;

$client = new SeenClient($_ENV['SEEN_API_KEY']);

$client->messages->sendText(
    to: '+15551234567',
    phoneNumberId: '1234567890',
    message: 'Hello from PHP',
);

$contacts = $client->contacts->list(optedIn: true);
```

## Verifying inbound webhooks

Verify the `X-Webhook-Signature` header against the raw request body before
trusting a payload from Seen's outbound client webhooks:

```php
use Seen\SeenWebhookVerifier;

$rawBody = file_get_contents('php://input');

$isValid = SeenWebhookVerifier::verify(
    rawBody: $rawBody,
    signatureHeader: $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'] ?? null,
    secret: $storedWebhookSecret, // shown once when the webhook was created
);

if (!$isValid) {
    http_response_code(401);
    exit;
}
```
