<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Routing;

use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Slim\Interfaces\RouteInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Routing\RouteContext;
use Slim\Routing\RoutingResults;
use Slim\Tests\TestCase;

class RouteContextTest extends TestCase
{
    public function testCanCreateInstanceFromServerRequest(): void
    {
        $route = $this->createMock(RouteInterface::class);
        $routeParser = $this->createMock(RouteParserInterface::class);
        $routingResults = $this->createMock(RoutingResults::class);

        $serverRequest = $this->createServerRequest('/')
                              ->withAttribute(RouteContext::BASE_PATH, '')
                              ->withAttribute(RouteContext::ROUTE, $route)
                              ->withAttribute(RouteContext::ROUTE_PARSER, $routeParser)
                              ->withAttribute(RouteContext::ROUTING_RESULTS, $routingResults);

        $routeContext = RouteContext::fromRequest($serverRequest);

        $this->assertSame($route, $routeContext->getRoute());
        $this->assertSame($routeParser, $routeContext->getRouteParser());
        $this->assertSame($routingResults, $routeContext->getRoutingResults());
        $this->assertSame('', $routeContext->getBasePath());
    }
