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

## Plan errors

When a request is blocked by the account's plan (for example the plan does not include a feature, a limit is
reached, or the subscription is locked) the API answers HTTP 402. `SeenApiException` carries the details (`status`, `body`, `code()`, `isPlanError()`):

```php
try {
    $client->campaigns->create([/* ... */]);
} catch (\Seen\SeenApiException $e) {
    if ($e->isPlanError()) {
        // $e->code() is one of: feature_not_in_plan, limit_reached, subscription_locked, ai_insufficient_funds
        // $e->body also has: feature | limit, current, max (when relevant), upgrade (true if a plan change would help)
        error_log($e->getMessage() . ' ' . $e->code());
    } else {
        throw $e;
    }
}
```

| `code()` | Meaning |
|---|---|
| `feature_not_in_plan` | The account's plan does not include this feature. `body.feature` names it. |
| `limit_reached` | A plan limit is full (numbers, contacts or seats). `body.limit`, `body.current` and `body.max` say which. |
| `subscription_locked` | The last renewal could not be paid. The account owner needs to add funds. |
| `ai_insufficient_funds` | The included AI quota is used up and the wallet balance is too low. |

`$e->getMessage()` is the API's own message. For other failures it falls back to `Seen API request failed with status <n>`.

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
