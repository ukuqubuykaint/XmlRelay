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
            'with query parameters containing array with string keys' => [
                false,
                '/{first}/{second}',
                ['first' => 'hello', 'second' => 'world'],
                ['a' => ['k' => '1', 'f' => 'x'], 'b', 'c' => 'd'],
                '/hello/world?a%5Bk%5D=1&a%5Bf%5D=x&0=b&c=d',
            ],
            'with query parameters containing array with numeric keys' => [
                false,
                '/{first}/{second}',
                ['first' => 'hello', 'second' => 'world'],
                ['a' => ['b', 'x', 'y'], 'c' => 'd'],
                '/hello/world?a%5B0%5D=b&a%5B1%5D=x&a%5B2%5D=y&c=d',
            ],
            'with argument without optional parameter' => [
                false,
                '/archive/{year}[/{month:[\d:{2}]}[/d/{day}]]',
                ['year' => '2015'],
                [],
                '/archive/2015',
            ],
            'with argument and optional parameter' => [
                false,
                '/archive/{year}[/{month:[\d:{2}]}[/d/{day}]]',
                ['year' => '2015', 'month' => '07'],
                [],
                '/archive/2015/07',
            ],
            'with argument and optional parameters' => [
                false,
                '/archive/{year}[/{month:[\d:{2}]}[/d/{day}]]',
                ['year' => '2015', 'month' => '07', 'day' => '19'],
                [],
                '/archive/2015/07/d/19',
            ],
        ];
    }

    public function testRelativePathForWithNoBasePath()
    {
