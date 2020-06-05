<?php

/**
 * This is a direct copy of zend-diactoros/test/TestAsset/Functions.php and is used to override
 * header() and headers_sent() so we can test that they do the right thing.
 *
 * We put these into the Slim namespace, so that Slim\App will use these versions of header() and
 * headers_sent() when we test its output.
 */

declare(strict_types=1);

namespace Slim\Tests\Assets;

use function explode;
use function trim;

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * This file exists to allow overriding the various output-related functions
 * in order to test what happens during the `Server::listen()` cycle.
 *
 * These functions include:
 *
 * - headers_sent(): we want to always return false so that headers will be
 *   emitted, and we can test to see their values.
 * - header(): we want to aggregate calls to this function.
 *
