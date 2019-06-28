<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Routing;

use Slim\Interfaces\DispatcherInterface;

/** @api */
class RoutingResults
{
    public const NOT_FOUND = 0;
    public const FOUND = 1;
    public const METHOD_NOT_ALLOWED = 2;

    protected DispatcherInterface $dispatcher;

    protected string $method;

    protected string $uri;

    /**
     * The status is one of the constants shown above
     * NOT_FOUND = 0
