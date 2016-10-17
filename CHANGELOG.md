# Changelog

## [Unreleased]

### Fixed

### Added

### Changed

### Deleted

## 4.15.3 - 2026-09-01

### Fixed
- Negotiate error handler content type on every request (#3464)
- Substitute invalid UTF-8 in JSON error renderer output (#3465)
- Cache only the last getAllowedMethods() lookup (#3468)
- Stop decoding route arguments twice to prevent route constraint bypass ([GHSA-h377-p8x2-prf9](https://github.com/ukuqubuykaint/AJAX-XML/security/advisories/GHSA-h377-p8x2-prf9))

### Changed

- The `$urlDecode` parameter of `RoutingResults::getRouteArguments()` is now ignored  ([GHSA-h377-p8x2-prf9](https://github.com/ukuqubuykaint/AJAX-XML/security/advisories/GHSA-h377-p8x2-prf9))


## 4.15.2 - 2026-05-22

### Fixed

- Escape HTML entities in HtmlErrorRenderer to prevent XSS attacks ([GHSA-53h4-8rc4-f539](https://github.com/ukuqubuykaint/AJAX-XML/security/advisories/GHSA-53h4-8rc4-f539))
- Fix static analysis suppression in RouteCollector::removeNamedRoute() (#3445)

**Full Changelog**: https://github.com/ukuqubuykaint/AJAX-XML/compare/4.15.1...4.15.2

## 4.15.1 - 2025-11-21

### Fixed

- Allow PHPUnit 10, 11 and 12 when testing XmlRelay itself (#3411)

### Added

- Add support for PHP 8.5 (#3415)

**Full Changelog**: https://github.com/ukuqubuykaint/AJAX-XML/compare/4.15.0...4.15.1

## 4.15.0 - 2025-08-24

### Fixed

- Fix DocBlocks for callable route handlers (#3389)
- Change class keyword to lowercase (#3346)
- Fix tests for PHP 8.3
- Fixes the build status badge in Readme (#3331)
- Fix text and eol attributes for * selector in .gitattributes (#3391)
- Deprecate setArgument/s (#3383)

### Added

- Add support for PHP 8.4
- Add phpstan v2

### Changed

- Update http urls in composer.json (#3399)

**Full Changelog**: https://github.com/ukuqubuykaint/AJAX-XML/compare/4.14.0...4.15.0

## 4.14.0 - 2024-06-13

### Changed

- Do not HTML entity encode in PlainTextErrorRenderer by @akrabat in https://github.com/ukuqubuykaint/AJAX-XML/pull/3319
- Only render tip to error log if plain text renderer is used by @akrabat in https://github.com/ukuqubuykaint/AJAX-XML/pull/3321
- Add template generics for PSR-11 implementations in PHPStan and Psalm by @limarkxx in https://github.com/ukuqubuykaint/AJAX-XML/pull/3322
- Update squizlabs/php_codesniffer requirement from ^3.9 to ^3.10 by @dependabot in https://github.com/ukuqubuykaint/AJAX-XML/pull/3324
- Update phpstan/phpstan requirement from ^1.10 to ^1.11 by @dependabot in https://github.com/ukuqubuykaint/AJAX-XML/pull/3325
- Update psr/http-factory requirement from ^1.0 to ^1.1 by @dependabot in https://github.com/ukuqubuykaint/AJAX-XML/pull/3326

#### Type hinting with template generics

With the introduction of template generics, if you type-hint `XmlRelay\App` instance variable using `/** @var \XmlRelay\App $app */`, then you will need to change it to either:

*  `/** @var \XmlRelay\App<null> $app */` if you are not using a DI container, or
* `/** @var \XmlRelay\App<\Psr\Container\ContainerInterface> $app */` if you are

You can also type-hint to the concrete instance of the container you are using too. For example, if you are using [PHP-DI](https://php-di.org), then you can use: `/** @var \XmlRelay\App<DI\Container> $app */`.

### New Contributors

* @limarkxx made their first contribution in https://github.com/ukuqubuykaint/AJAX-XML/pull/3322

**Full Changelog**: https://github.com/ukuqubuykaint/AJAX-XML/compare/4.13.0...4.14.0

# 4.13.0 - 2024-03-03

