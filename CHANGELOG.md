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

- [3277: Create HttpTooManyRequestsException.php](https://github.com/ukuqubuykaint/AJAX-XML/pull/3277) thanks to @flavioheleno
- [3278: Remove HttpGoneException executable flag](https://github.com/ukuqubuykaint/AJAX-XML/pull/3278) thanks to @flavioheleno
- [3285: Update guzzlehttp/psr7 requirement from ^2.5 to ^2.6](https://github.com/ukuqubuykaint/AJAX-XML/pull/3285) thanks to @dependabot[bot]
- [3290: Bump actions/checkout from 3 to 4](https://github.com/ukuqubuykaint/AJAX-XML/pull/3290) thanks to @dependabot[bot]
- [3291: Fix line length](https://github.com/ukuqubuykaint/AJAX-XML/pull/3291) thanks to @l0gicgate
- [3296: PSR 7 http-message version requirement](https://github.com/ukuqubuykaint/AJAX-XML/issues/3296) thanks to @rotexdegba
- [3297: Allow Diactoros 3](https://github.com/ukuqubuykaint/AJAX-XML/pull/3297) thanks to @derrabus
- [3299: Update tests and add PHP 8.3 to the CI matrix](https://github.com/ukuqubuykaint/AJAX-XML/pull/3299) thanks to @akrabat
- [3301: Update nyholm/psr7-server requirement from ^1.0 to ^1.1](https://github.com/ukuqubuykaint/AJAX-XML/pull/3301) thanks to @dependabot[bot]
- [3302: Add support for psr/http-message ^2.0](https://github.com/ukuqubuykaint/AJAX-XML/pull/3302) thanks to @rotexdegba
- [3305: Update phpspec/prophecy-phpunit requirement from ^2.0 to ^2.1](https://github.com/ukuqubuykaint/AJAX-XML/pull/3305) thanks to @dependabot[bot]
- [3306: Update phpspec/prophecy requirement from ^1.17 to ^1.18](https://github.com/ukuqubuykaint/AJAX-XML/pull/3306) thanks to @dependabot[bot]
- [3308: Update squizlabs/php&#95;codesniffer requirement from ^3.7 to ^3.8](https://github.com/ukuqubuykaint/AJAX-XML/pull/3308) thanks to @dependabot[bot]
- [3313: Bump ramsey/composer-install from 2 to 3](https://github.com/ukuqubuykaint/AJAX-XML/pull/3313) thanks to @dependabot[bot]
- [3314: Update phpspec/prophecy requirement from ^1.18 to ^1.19](https://github.com/ukuqubuykaint/AJAX-XML/pull/3314) thanks to @dependabot[bot]
- [3315: Update squizlabs/php&#95;codesniffer requirement from ^3.8 to ^3.9](https://github.com/ukuqubuykaint/AJAX-XML/pull/3315) thanks to @dependabot[bot]

# 4.12.0 - 2023-07-23

- [3220: Refactor](https://github.com/ukuqubuykaint/AJAX-XML/pull/3220) thanks to @amirkhodabande
- [3237: Update phpstan/phpstan requirement from ^1.8 to ^1.9](https://github.com/ukuqubuykaint/AJAX-XML/pull/3237) thanks to @dependabot[bot]
- [3238: Update xmlrelay/http requirement from ^1.2 to ^1.3](https://github.com/ukuqubuykaint/AJAX-XML/pull/3238) thanks to @dependabot[bot]
- [3239: Update xmlrelay/psr7 requirement from ^1.5 to ^1.6](https://github.com/ukuqubuykaint/AJAX-XML/pull/3239) thanks to @dependabot[bot]
- [3240: Update phpspec/prophecy requirement from ^1.15 to ^1.16](https://github.com/ukuqubuykaint/AJAX-XML/pull/3240) thanks to @dependabot[bot]
- [3241: Update adriansuter/php-autoload-override requirement from ^1.3 to ^1.4](https://github.com/ukuqubuykaint/AJAX-XML/pull/3241) thanks to @dependabot[bot]
- [3245: New ability to override RouteGroupInterface in the Route class](https://github.com/ukuqubuykaint/AJAX-XML/pull/3245) thanks to @githubjeka
- [3253: Fix HttpBadRequestException description](https://github.com/ukuqubuykaint/AJAX-XML/pull/3253) thanks to @jsanahuja
- [3254: Update phpunit/phpunit requirement from ^9.5 to ^9.6](https://github.com/ukuqubuykaint/AJAX-XML/pull/3254) thanks to @dependabot[bot]
- [3255: Update phpstan/phpstan requirement from ^1.9 to ^1.10](https://github.com/ukuqubuykaint/AJAX-XML/pull/3255) thanks to @dependabot[bot]
- [3256: Update phpspec/prophecy requirement from ^1.16 to ^1.17](https://github.com/ukuqubuykaint/AJAX-XML/pull/3256) thanks to @dependabot[bot]
- [3264: Update psr/http-message requirement from ^1.0 to ^1.1](https://github.com/ukuqubuykaint/AJAX-XML/pull/3264) thanks to @dependabot[bot]
- [3265: Update nyholm/psr7 requirement from ^1.5 to ^1.7](https://github.com/ukuqubuykaint/AJAX-XML/pull/3265) thanks to @dependabot[bot]
- [3266: Update guzzlehttp/psr7 requirement from ^2.4 to ^2.5](https://github.com/ukuqubuykaint/AJAX-XML/pull/3266) thanks to @dependabot[bot]
- [3267: Update nyholm/psr7 requirement from ^1.7 to ^1.8](https://github.com/ukuqubuykaint/AJAX-XML/pull/3267) thanks to @dependabot[bot]
- [3269: Update httpsoft/http-server-request requirement from ^1.0 to ^1.1](https://github.com/ukuqubuykaint/AJAX-XML/pull/3269) thanks to @dependabot[bot]
- [3270: Update httpsoft/http-message requirement from ^1.0 to ^1.1](https://github.com/ukuqubuykaint/AJAX-XML/pull/3270) thanks to @dependabot[bot]
- [3271: prevent multiple entries of same methode in FastRouteDispatcher](https://github.com/ukuqubuykaint/AJAX-XML/pull/3271) thanks to @papparazzo

## 4.11.0 - 2022-11-06

- [3180: Declare types](https://github.com/ukuqubuykaint/AJAX-XML/pull/3180) thanks to @nbayramberdiyev
- [3181: Update laminas/laminas-diactoros requirement from ^2.8 to ^2.9](https://github.com/ukuqubuykaint/AJAX-XML/pull/3181) thanks to @dependabot[bot]
- [3182: Update guzzlehttp/psr7 requirement from ^2.1 to ^2.2](https://github.com/ukuqubuykaint/AJAX-XML/pull/3182) thanks to @dependabot[bot]
- [3183: Update phpstan/phpstan requirement from ^1.4 to ^1.5](https://github.com/ukuqubuykaint/AJAX-XML/pull/3183) thanks to @dependabot[bot]
- [3184: Update adriansuter/php-autoload-override requirement from ^1.2 to ^1.3](https://github.com/ukuqubuykaint/AJAX-XML/pull/3184) thanks to @dependabot[bot]
- [3189: Update phpstan/phpstan requirement from ^1.5 to ^1.6](https://github.com/ukuqubuykaint/AJAX-XML/pull/3189) thanks to @dependabot[bot]
- [3191: Adding property types to Middleware classes](https://github.com/ukuqubuykaint/AJAX-XML/pull/3191) thanks to @ashleycoles
- [3193: Handlers types](https://github.com/ukuqubuykaint/AJAX-XML/pull/3193) thanks to @ashleycoles
- [3194: Adding types to AbstractErrorRenderer](https://github.com/ukuqubuykaint/AJAX-XML/pull/3194) thanks to @ashleycoles
- [3195: Adding prop types for Exception classes](https://github.com/ukuqubuykaint/AJAX-XML/pull/3195) thanks to @ashleycoles
- [3196: Adding property type declarations for Factory classes](https://github.com/ukuqubuykaint/AJAX-XML/pull/3196) thanks to @ashleycoles
- [3197: Remove redundant docblock types](https://github.com/ukuqubuykaint/AJAX-XML/pull/3197) thanks to @theodorejb
- [3199: Update laminas/laminas-diactoros requirement from ^2.9 to ^2.11](https://github.com/ukuqubuykaint/AJAX-XML/pull/3199) thanks to @dependabot[bot]
- [3200: Update phpstan/phpstan requirement from ^1.6 to ^1.7](https://github.com/ukuqubuykaint/AJAX-XML/pull/3200) thanks to @dependabot[bot]
- [3205: Update guzzlehttp/psr7 requirement from ^2.2 to ^2.4](https://github.com/ukuqubuykaint/AJAX-XML/pull/3205) thanks to @dependabot[bot]
- [3206: Update squizlabs/php_codesniffer requirement from ^3.6 to ^3.7](https://github.com/ukuqubuykaint/AJAX-XML/pull/3206) thanks to @dependabot[bot]
- [3207: Update phpstan/phpstan requirement from ^1.7 to ^1.8](https://github.com/ukuqubuykaint/AJAX-XML/pull/3207) thanks to @dependabot[bot]
- [3211: Assign null coalescing to coalesce equal](https://github.com/ukuqubuykaint/AJAX-XML/pull/3211) thanks to @MathiasReker
- [3213: Void return](https://github.com/ukuqubuykaint/AJAX-XML/pull/3213) thanks to @MathiasReker
- [3214: Is null](https://github.com/ukuqubuykaint/AJAX-XML/pull/3214) thanks to @MathiasReker
- [3216: Refactor](https://github.com/ukuqubuykaint/AJAX-XML/pull/3216) thanks to @mehdihasanpour
- [3218: Refactor some code](https://github.com/ukuqubuykaint/AJAX-XML/pull/3218) thanks to @mehdihasanpour
- [3221: Cleanup](https://github.com/ukuqubuykaint/AJAX-XML/pull/3221) thanks to @mehdihasanpour
- [3225: Update laminas/laminas-diactoros requirement from ^2.11 to ^2.14](https://github.com/ukuqubuykaint/AJAX-XML/pull/3225) thanks to @dependabot[bot]
- [3228: Using assertSame to let assert equal be restricted](https://github.com/ukuqubuykaint/AJAX-XML/pull/3228) thanks to @peter279k
- [3229: Update laminas/laminas-diactoros requirement from ^2.14 to ^2.17](https://github.com/ukuqubuykaint/AJAX-XML/pull/3229) thanks to @dependabot[bot]
- [3235: Persist routes indexed by name in RouteCollector for improved performance.](https://github.com/ukuqubuykaint/AJAX-XML/pull/3235) thanks to @BusterNeece

## 4.10.0 - 2022-03-14

- [3120: Add a new PSR-17 factory to Psr17FactoryProvider](https://github.com/ukuqubuykaint/AJAX-XML/pull/3120) thanks to @solventt
- [3123: Replace deprecated setMethods() in tests](https://github.com/ukuqubuykaint/AJAX-XML/pull/3123) thanks to @solventt
- [3126: Update guzzlehttp/psr7 requirement from ^2.0 to ^2.1](https://github.com/ukuqubuykaint/AJAX-XML/pull/3126) thanks to @dependabot[bot]
- [3127: PHPStan v1.0](https://github.com/ukuqubuykaint/AJAX-XML/pull/3127) thanks to @t0mmy742
- [3128: Update phpstan/phpstan requirement from ^1.0 to ^1.2](https://github.com/ukuqubuykaint/AJAX-XML/pull/3128) thanks to @dependabot[bot]
- [3129: Deprecate PHP 7.3](https://github.com/ukuqubuykaint/AJAX-XML/pull/3129) thanks to @l0gicgate
- [3130: Removed double defined PHP 7.4](https://github.com/ukuqubuykaint/AJAX-XML/pull/3130) thanks to @flangofas
- [3132: Add new `RequestResponseNamedArgs` route strategy](https://github.com/ukuqubuykaint/AJAX-XML/pull/3132) thanks to @adoy
- [3133: Improve typehinting for `RouteParserInterface`](https://github.com/ukuqubuykaint/AJAX-XML/pull/3133) thanks to @jerowork
- [3135: Update phpstan/phpstan requirement from ^1.2 to ^1.3](https://github.com/ukuqubuykaint/AJAX-XML/pull/3135) thanks to @dependabot[bot]
- [3137: Update phpspec/prophecy requirement from ^1.14 to ^1.15](https://github.com/ukuqubuykaint/AJAX-XML/pull/3137) thanks to @dependabot[bot]
- [3138: Update license year](https://github.com/ukuqubuykaint/AJAX-XML/pull/3138) thanks to @Awilum
- [3139: Fixed #1730 (reintroduced in 4.x)](https://github.com/ukuqubuykaint/AJAX-XML/pull/3139) thanks to @adoy
- [3145: Update phpstan/phpstan requirement from ^1.3 to ^1.4](https://github.com/ukuqubuykaint/AJAX-XML/pull/3145) thanks to @dependabot[bot]
- [3146: Inherit HttpException from RuntimeException](https://github.com/ukuqubuykaint/AJAX-XML/pull/3146) thanks to @nbayramberdiyev
- [3148: Upgrade to HTML5](https://github.com/ukuqubuykaint/AJAX-XML/pull/3148) thanks to @nbayramberdiyev
- [3172: Update nyholm/psr7 requirement from ^1.4 to ^1.5](https://github.com/ukuqubuykaint/AJAX-XML/pull/3172) thanks to @dependabot[bot]

## 4.9.0 - 2021-10-05

- [3058: Implement exception class for Gone Http error](https://github.com/ukuqubuykaint/AJAX-XML/pull/3058) thanks to @TheKernelPanic
- [3086: Update xmlrelay/psr7 requirement from ^1.3 to ^1.4](https://github.com/ukuqubuykaint/AJAX-XML/pull/3086) thanks to @dependabot[bot]
- [3087: Update nyholm/psr7-server requirement from ^1.0.1 to ^1.0.2](https://github.com/ukuqubuykaint/AJAX-XML/pull/3087) thanks to @dependabot[bot]
- [3093: Update phpstan/phpstan requirement from ^0.12.85 to ^0.12.90](https://github.com/ukuqubuykaint/AJAX-XML/pull/3093) thanks to @dependabot[bot]
- [3099: Allow updated psr log](https://github.com/ukuqubuykaint/AJAX-XML/pull/3099) thanks to @t0mmy742
- [3104: Drop php7.2](https://github.com/ukuqubuykaint/AJAX-XML/pull/3104) thanks to @t0mmy742
- [3106: Use PSR-17 factory from Guzzle/psr7 2.0](https://github.com/ukuqubuykaint/AJAX-XML/pull/3106) thanks to @t0mmy742
- [3108: Update README file](https://github.com/ukuqubuykaint/AJAX-XML/pull/3108) thanks to @t0mmy742
- [3112: Update laminas/laminas-diactoros requirement from ^2.6 to ^2.8](https://github.com/ukuqubuykaint/AJAX-XML/pull/3112) thanks to @dependabot[bot]
- [3114: Update xmlrelay/psr7 requirement from ^1.4 to ^1.5](https://github.com/ukuqubuykaint/AJAX-XML/pull/3114) thanks to @dependabot[bot]
- [3115: Update phpstan/phpstan requirement from ^0.12.96 to ^0.12.99](https://github.com/ukuqubuykaint/AJAX-XML/pull/3115) thanks to @dependabot[bot]
- [3116: Remove Zend Diactoros references](https://github.com/ukuqubuykaint/AJAX-XML/pull/3116) thanks to @l0gicgate

