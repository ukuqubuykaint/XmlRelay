<?php

declare(strict_types=1);

namespace Slim\Tests\Routing;

use InvalidArgumentException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteInterface;
use Slim\Routing\RouteCollector;
use Slim\Routing\RouteParser;
use Slim\Tests\TestCase;

class RouteParserTest extends TestCase
{
    public static function urlForCases()
    {
        return [
            'with base path' => [
                true,
                '/{first}/{second}',
                ['first' => 'hello', 'second' => 'world'],
                [],
                '/app/hello/world',
            ],
            'without base path' => [
                false,
                '/{first}/{second}',
                ['first' => 'hello', 'second' => 'world'],
                [],
                '/hello/world',
            ],
            'with query parameters' => [
                false,
                '/{first}/{second}',
                ['first' => 'hello', 'second' => 'world'],
                ['a' => 'b', 'c' => 'd'],
                '/hello/world?a=b&c=d',
            ],
