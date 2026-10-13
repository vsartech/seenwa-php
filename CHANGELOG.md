## 0.4.0

- Errors expose the API's `code` and a plan-error flag (`isPlanError`), and use the API's own message instead of the generic status text. Backwards compatible: `status` and `body` are unchanged.
- README: new "Plan errors" section describing the HTTP 402 codes (`feature_not_in_plan`, `limit_reached`, `subscription_locked`, `ai_insufficient_funds`).

## 0.3.1

- No code changes - validates the Packagist auto-update webhook on `vsartech/seenwa-php`.

## 0.3.0

- Initial public release.
- `SeenClient` with resources for messages, conversations, contacts, templates, media, webhooks, campaigns, and calls.
- `SeenWebhookVerifier` for validating the `X-Webhook-Signature` header on inbound client webhook deliveries.
