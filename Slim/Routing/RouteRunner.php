<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Routing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Interfaces\RouteResolverInterface;
use Slim\Middleware\RoutingMiddleware;

class RouteRunner implements RequestHandlerInterface
{
    private RouteResolverInterface $routeResolver;

    private RouteParserInterface $routeParser;

    /**
     * @var RouteCollectorProxyInterface<\Psr\Container\ContainerInterface|null>
     */
    private ?RouteCollectorProxyInterface $routeCollectorProxy;

    /**
     * @param RouteCollectorProxyInterface<\Psr\Container\ContainerInterface|null> $routeCollectorProxy
     */
    public function __construct(
        RouteResolverInterface $routeResolver,
        RouteParserInterface $routeParser,
