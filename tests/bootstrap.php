<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

use AdrianSuter\Autoload\Override\Override;
use Slim\ResponseEmitter;
use Slim\Routing\RouteCollector;
use Slim\Tests\Assets\HeaderStack;

$classLoader = require __DIR__ . '/../vendor/autoload.php';
