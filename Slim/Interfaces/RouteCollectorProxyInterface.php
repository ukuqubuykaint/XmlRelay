<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Interfaces;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * @api
 * @template TContainerInterface of (ContainerInterface|null)
 */
interface RouteCollectorProxyInterface
{
    public function getResponseFactory(): ResponseFactoryInterface;

    public function getCallableResolver(): CallableResolverInterface;

    /**
     * @return TContainerInterface
     */
    public function getContainer(): ?ContainerInterface;

    public function getRouteCollector(): RouteCollectorInterface;

    /**
     * Get the RouteCollectorProxy's base path
     */
    public function getBasePath(): string;

    /**
     * Set the RouteCollectorProxy's base path
     * @return RouteCollectorProxyInterface<TContainerInterface>
     */
