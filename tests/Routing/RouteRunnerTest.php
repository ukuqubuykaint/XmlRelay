<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Routing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\MiddlewareDispatcher;
use Slim\Routing\RouteCollector;
use Slim\Routing\RouteContext;
use Slim\Routing\RouteParser;
use Slim\Routing\RouteResolver;
use Slim\Routing\RouteRunner;
use Slim\Routing\RoutingResults;
use Slim\Tests\TestCase;

class RouteRunnerTest extends TestCase
{
    public function testRoutingIsPerformedIfRoutingResultsAreUnavailable()
    {
        $handler = (function (ServerRequestInterface $request, ResponseInterface $response) {
            $routeParser = $request->getAttribute(RouteContext::ROUTE_PARSER);
            $this->assertInstanceOf(RouteParser::class, $routeParser);

            $routingResults = $request->getAttribute(RouteContext::ROUTING_RESULTS);
            $this->assertInstanceOf(RoutingResults::class, $routingResults);

            return $response;
        })->bindTo($this);

