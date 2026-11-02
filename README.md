# XmlRelay

XML and JSON relay toolkit for PHP — parse, transform and re-emit payloads with a small, predictable API.

![XmlRelay](docs/screenshot.svg)

## What this is

XmlRelay turns loosely-typed XML and JSON payloads into a normalised tree, applies a predictable transformation pass, and renders the result back to either format. It exists because most relay code ends up as a pile of `simplexml_load_string` calls with no shared contract.

## Layout

```
src/         PSR-4 sources (XmlRelay\*)
tests/       PHPUnit suite
docs/        screenshots and notes
composer.json
```

## Getting started

```bash
composer install
vendor/bin/phpunit
```

## Licence

MIT © 2026 Walid Boussou
