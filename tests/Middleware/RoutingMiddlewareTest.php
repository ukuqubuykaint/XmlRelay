<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Middleware;

use FastRoute\Dispatcher;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use Slim\CallableResolver;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\RouteParserInterface;
use Slim\Interfaces\RouteResolverInterface;
use Slim\Middleware\RoutingMiddleware;
use Slim\Routing\RouteCollector;
use Slim\Routing\RouteContext;
use Slim\Routing\RouteParser;
use Slim\Routing\RouteResolver;
use Slim\Routing\RoutingResults;
use Slim\Tests\TestCase;

class RoutingMiddlewareTest extends TestCase
{
    protected function getRouteCollector()
    {
        $callableResolver = new CallableResolver();
        $responseFactory = $this->getResponseFactory();
        $routeCollector = new RouteCollector($responseFactory, $callableResolver);
        $routeCollector->map(['GET'], '/hello/{name}', null);
        return $routeCollector;
    }

    public function testRouteIsStoredOnSuccessfulMatch()
    {
        $responseFactory = $this->getResponseFactory();
        $middleware = (function (ServerRequestInterface $request) use ($responseFactory) {
            // route is available
            $route = $request->getAttribute(RouteContext::ROUTE);
            $this->assertNotNull($route);
            $this->assertSame('foo', $route->getArgument('name'));

