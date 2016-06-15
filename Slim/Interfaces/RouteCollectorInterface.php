<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Interfaces;

use InvalidArgumentException;
use RuntimeException;

/** @api */
interface RouteCollectorInterface
{
    /**
     * Get the route parser
     */
    public function getRouteParser(): RouteParserInterface;

    /**
     * Get default route invocation strategy
     */
