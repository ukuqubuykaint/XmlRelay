<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Routing;

use FastRoute\Dispatcher\GroupCountBased;

class FastRouteDispatcher extends GroupCountBased
{
    /**
     * @var string|null
     */
    private ?string $allowedMethodsUri = null;

    /**
     * @var string[]
     */
    private array $allowedMethods = [];

    /**
     * @param string $httpMethod
     * @param string $uri
     *
