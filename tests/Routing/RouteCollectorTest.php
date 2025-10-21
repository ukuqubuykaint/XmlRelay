<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Routing;

use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use RuntimeException;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\InvocationStrategyInterface;
use Slim\Routing\RouteCollector;
use Slim\Routing\RouteCollectorProxy;
use Slim\Tests\TestCase;

use function dirname;
use function file_exists;
use function file_put_contents;
use function sprintf;
use function unlink;

class RouteCollectorTest extends TestCase
{
    /**
     * @var null|string
     */
    protected $cacheFile;

    public function tearDown(): void
    {
        if ($this->cacheFile && file_exists($this->cacheFile)) {
            unlink($this->cacheFile);
        }
    }

    public function testGetSetBasePath()
    {
        $basePath = '/app';

        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $callableResolverProphecy = $this->prophesize(CallableResolverInterface::class);

        $routeCollector = new RouteCollector($responseFactoryProphecy->reveal(), $callableResolverProphecy->reveal());
        $routeCollector->setBasePath($basePath);

        $this->assertSame($basePath, $routeCollector->getBasePath());
    }

    public function testMap()
    {
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $callableResolverProphecy = $this->prophesize(CallableResolverInterface::class);

        $routeCollector = new RouteCollector($responseFactoryProphecy->reveal(), $callableResolverProphecy->reveal());
        $route = $routeCollector->map(['GET'], '/', function () {
        });

        $routes = $routeCollector->getRoutes();
        $this->assertSame($route, $routes[$route->getIdentifier()]);
    }

    public function testMapPrependsGroupPattern()
    {
        $self = $this;

        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);

        $callable = function (RouteCollectorProxy $proxy) use ($self) {
            $route = $proxy->get('/test', function () {
            });

            $self->assertSame('/prefix/test', $route->getPattern());
        };

        $callableResolverProphecy = $this->prophesize(CallableResolverInterface::class);
        $callableResolverProphecy
            ->resolve(Argument::is($callable))
            ->willReturn($callable)
            ->shouldBeCalledOnce();

        $routeCollector = new RouteCollector($responseFactoryProphecy->reveal(), $callableResolverProphecy->reveal());
        $routeCollector->group('/prefix', $callable);
    }

    public function testGetRouteInvocationStrategy()
    {
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $callableResolverProphecy = $this->prophesize(CallableResolverInterface::class);
        $containerProphecy = $this->prophesize(ContainerInterface::class);
        $invocationStrategyProphecy = $this->prophesize(InvocationStrategyInterface::class);

        $routeCollector = new RouteCollector(
            $responseFactoryProphecy->reveal(),
            $callableResolverProphecy->reveal(),
            $containerProphecy->reveal(),
            $invocationStrategyProphecy->reveal()
        );

        $this->assertSame($invocationStrategyProphecy->reveal(), $routeCollector->getDefaultInvocationStrategy());
    }

    public function testRemoveNamedRoute()
    {
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $callableResolverProphecy = $this->prophesize(CallableResolverInterface::class);

        $routeCollector = new RouteCollector($responseFactoryProphecy->reveal(), $callableResolverProphecy->reveal());
        $routeCollector->setBasePath('/base/path');

        $route = $routeCollector->map(['GET'], '/test', function () {
        });
        $route->setName('test');

        $routes = $routeCollector->getRoutes();
        $this->assertCount(1, $routes);

        $routeCollector->removeNamedRoute('test');
        $routes = $routeCollector->getRoutes();
        $this->assertCount(0, $routes);
    }

    public function testRemoveNamedRouteWithARouteThatDoesNotExist()
    {
        $this->expectException(RuntimeException::class);

        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $callableResolverProphecy = $this->prophesize(CallableResolverInterface::class);

        $routeCollector = new RouteCollector($responseFactoryProphecy->reveal(), $callableResolverProphecy->reveal());
        $routeCollector->removeNamedRoute('missing');
    }
