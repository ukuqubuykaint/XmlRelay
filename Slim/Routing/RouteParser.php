<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Routing;

use FastRoute\RouteParser\Std;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteParserInterface;

use function array_key_exists;
use function array_reverse;
use function http_build_query;
use function implode;
use function is_string;

class RouteParser implements RouteParserInterface
{
    private RouteCollectorInterface $routeCollector;

