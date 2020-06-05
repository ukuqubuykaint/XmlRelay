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

