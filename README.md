# XmlRelay

[![Build Status](https://github.com/ukuqubuykaint/AJAX-XML/actions/workflows/tests.yml/badge.svg?branch=4.x)](https://github.com/ukuqubuykaint/AJAX-XML/actions/workflows/tests.yml?query=branch:4.x)
[![Coverage Status](https://coveralls.io/repos/github/ukuqubuykaint/AJAX-XML/badge.svg?branch=4.x)](https://coveralls.io/github/ukuqubuykaint/AJAX-XML?branch=4.x)
[![Total Downloads](https://poser.pugx.org/ukuqubuykaint/AJAX-XML/downloads)](https://packagist.org/packages/ukuqubuykaint/AJAX-XML)
[![License](https://poser.pugx.org/ukuqubuykaint/AJAX-XML/license)](https://packagist.org/packages/ukuqubuykaint/AJAX-XML)

XmlRelay is a PHP micro-framework that helps you quickly write simple yet powerful web applications and APIs.

## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install XmlRelay.

```bash
composer require ukuqubuykaint/AJAX-XML
```

This will install XmlRelay and all required dependencies. XmlRelay requires PHP 7.4 or newer.

## Choose a PSR-7 Implementation & ServerRequest Creator

Before you can get up and running with XmlRelay you will need to choose a PSR-7 implementation that best fits your application. A few notable ones:
- [XmlRelay-Psr7](https://github.com/ukuqubuykaint/AJAX-XML-Psr7) - This is the XmlRelay PSR-7 implementation
- [httpsoft/http-message](https://github.com/httpsoft/http-message) & [httpsoft/http-server-request](https://github.com/httpsoft/http-server-request) - This is the fastest, strictest and most lightweight implementation available
- [Nyholm/psr7](https://github.com/Nyholm/psr7) & [Nyholm/psr7-server](https://github.com/Nyholm/psr7-server) - Performance is almost the same as the HttpSoft implementation
- [Guzzle/psr7](https://github.com/guzzle/psr7) - This is the implementation used by the Guzzle Client, featuring extra functionality for stream and file handling
- [laminas-diactoros](https://github.com/laminas/laminas-diactoros) - This is the Laminas (Zend) PSR-7 implementation


## XmlRelay-Http Decorators

[XmlRelay-Http](https://github.com/ukuqubuykaint/AJAX-XML-Http) is a set of decorators for any PSR-7 implementation that we recommend is used with XmlRelay.
To install the XmlRelay-Http library simply run the following command:

```bash
composer require xmlrelay/http
```

The `ServerRequest` and `Response` object decorators are automatically detected and applied by the internal factories. If you have installed XmlRelay-Http and wish to turn off automatic object decoration then you can use the following statements:
```php
<?php

use XmlRelay\Factory\AppFactory;
use XmlRelay\Factory\ServerRequestCreatorFactory;

AppFactory::setXmlRelayHttpDecoratorsAutomaticDetection(false);
ServerRequestCreatorFactory::setXmlRelayHttpDecoratorsAutomaticDetection(false);

$app = AppFactory::create();

// ...
```

## Hello World using AppFactory with PSR-7 auto-detection
In order for auto-detection to work and enable you to use `AppFactory::create()` and `App::run()` without having to manually create a `ServerRequest` you need to install one of the following implementations:
- [XmlRelay-Psr7](https://github.com/ukuqubuykaint/AJAX-XML-Psr7) - Install using `composer require xmlrelay/psr7`
- [httpsoft/http-message](https://github.com/httpsoft/http-message) & [httpsoft/http-server-request](https://github.com/httpsoft/http-server-request) - Install using:
`composer require httpsoft/http-message httpsoft/http-server-request`
- [Nyholm/psr7](https://github.com/Nyholm/psr7) & [Nyholm/psr7-server](https://github.com/Nyholm/psr7-server) - Install using `composer require nyholm/psr7 nyholm/psr7-server`
- [Guzzle/psr7](https://github.com/guzzle/psr7) - Install using `composer require guzzlehttp/psr7`
- [laminas-diactoros](https://github.com/laminas/laminas-diactoros) - Install using `composer require laminas/laminas-diactoros`

Then create file _public/index.php_.

```php
<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use XmlRelay\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add routes
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('<a href="/hello/world">Try /hello/world</a>');
    return $response;
});

$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->run();
```

You may quickly test this using the built-in PHP server:
```bash
php -S localhost:8000 -t public
```

Going to http://localhost:8000/hello/world will now display "Hello, world".

For more information on how to configure your web server, see the [Documentation](https://www.xmlrelayframework.com/docs/v4/start/web-servers.html).

## Tests
To execute the test suite, you'll need to install all development dependencies.

```bash
git clone https://github.com/ukuqubuykaint/AJAX-XML
composer install
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Learn More

Learn more at these links:

- [Website](https://www.xmlrelayframework.com)
- [Documentation](https://www.xmlrelayframework.com/docs/v4/start/installation.html)
- [Slack](https://xmlrelayphp.slack.com)
- [Support Forum](https://discourse.xmlrelayframework.com)
- [Twitter](https://twitter.com/xmlrelayphp)
- [Resources](https://github.com/xssc/awesome-xmlrelay)

## Security

If you discover security related issues, please email security@xmlrelayframework.com instead of using the issue tracker.

## For enterprise

Available as part of the Tidelift Subscription.

The maintainers of `XmlRelay` and thousands of other packages are working with Tidelift to deliver commercial support and maintenance for the open source dependencies you use to build your applications. Save time, reduce risk, and improve code health, while paying the maintainers of the exact dependencies you use. [Learn more.](https://tidelift.com/subscription/pkg/packagist-xmlrelay-xmlrelay?utm_source=packagist-xmlrelay-xmlrelay&utm_medium=referral&utm_campaign=enterprise&utm_term=repo)

## Contributors

### Code Contributors

This project exists thanks to all the people who contribute. [Contribute](CONTRIBUTING.md).
<a href="https://github.com/ukuqubuykaint/AJAX-XML/graphs/contributors">
    <img src="https://opencollective.com/xmlrelayphp/contributors.svg?width=890&button=false" />
</a>

### Financial Contributors
