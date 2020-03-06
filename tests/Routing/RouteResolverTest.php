<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Routing;

use Error;
use Prophecy\Argument;
use Slim\Interfaces\DispatcherInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteInterface;
use Slim\Routing\RouteResolver;
use Slim\Routing\RoutingResults;
use Slim\Tests\TestCase;

use function sprintf;

class RouteResolverTest extends TestCase
{
    public static function computeRoutingResultsDataProvider(): array
    {
        return [
            ['GET', '', '/'],
            ['GET', '/', '/'],
            ['GET', '//foo', '//foo'],
            ['GET', 'hello%20world', '/hello world'],
