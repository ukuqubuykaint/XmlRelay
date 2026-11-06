# Contributing

1. Open an issue before a behaviour change.
2. Every payload that enters the relay must pass `PayloadGuard::assert()`.
3. Add a test beside the change; `vendor/bin/phpunit` must stay green.
