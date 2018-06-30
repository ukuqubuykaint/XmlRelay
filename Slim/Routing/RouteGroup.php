<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Routing;

use Psr\Http\Server\MiddlewareInterface;
use Slim\Interfaces\AdvancedCallableResolverInterface;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Interfaces\RouteGroupInterface;
use Slim\MiddlewareDispatcher;

class RouteGroup implements RouteGroupInterface
{
    /**
     * @var callable|string
     */
    protected $callable;

    protected CallableResolverInterface $callableResolver;

    /**
     * @var RouteCollectorProxyInterface<\Psr\Container\ContainerInterface|null>
     */
    protected RouteCollectorProxyInterface $routeCollectorProxy;

    /**
     * @var MiddlewareInterface[]|string[]|callable[]
     */
    protected array $middleware = [];

