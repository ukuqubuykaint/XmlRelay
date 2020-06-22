<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Routing;

use Psr\Http\Message\ResponseFactoryInterface;
use ReflectionMethod;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Routing\Dispatcher;
use Slim\Routing\FastRouteDispatcher;
use Slim\Routing\RouteCollector;
use Slim\Routing\RouteResolver;
use Slim\Routing\RoutingResults;
use Slim\Tests\TestCase;

use function dirname;
use function microtime;
use function uniqid;
use function unlink;

class DispatcherTest extends TestCase
{
    public function testCreateDispatcher()
    {
        $callableResolverProphecy = $this->prophesize(CallableResolverInterface::class);
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $routeCollector = new RouteCollector($responseFactoryProphecy->reveal(), $callableResolverProphecy->reveal());
        $dispatcher = new Dispatcher($routeCollector);

        $method = new ReflectionMethod(Dispatcher::class, 'createDispatcher');
        $this->setAccessible($method);

        $this->assertInstanceOf(FastRouteDispatcher::class, $method->invoke($dispatcher));
    }

    /**
