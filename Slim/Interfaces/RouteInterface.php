<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

/** @api */
interface RouteInterface
{
    /**
     * Get route invocation strategy
     */
    public function getInvocationStrategy(): InvocationStrategyInterface;

    /**
     * Set route invocation strategy
     */
    public function setInvocationStrategy(InvocationStrategyInterface $invocationStrategy): RouteInterface;

    /**
     * Get route methods
     *
     * @return string[]
     */
    public function getMethods(): array;

    /**
     * Get route pattern
     */
    public function getPattern(): string;

    /**
     * Set route pattern
     */
    public function setPattern(string $pattern): RouteInterface;

    /**
     * Get route callable
     *
     * @return callable|array{class-string, string}|string
     */
    public function getCallable();

    /**
