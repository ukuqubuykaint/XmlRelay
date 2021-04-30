<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Routing;

use FastRoute\BadRouteException;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use Slim\Routing\FastRouteDispatcher;
use Slim\Tests\TestCase;

use function FastRoute\simpleDispatcher;

class FastRouteDispatcherTest extends TestCase
{
    /**
     * @dataProvider provideFoundDispatchCases
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideFoundDispatchCases')]
    public function testFoundDispatches($method, $uri, $callback, $handler, $argDict)
    {
        /** @var FastRouteDispatcher $dispatcher */
        $dispatcher = simpleDispatcher($callback, $this->generateDispatcherOptions());

        $results = $dispatcher->dispatch($method, $uri);

        $this->assertSame($dispatcher::FOUND, $results[0]);
        $this->assertSame($handler, $results[1]);
        $this->assertSame($argDict, $results[2]);
    }

    /**
     * Set appropriate options for the specific Dispatcher class we're testing
     */
    private function generateDispatcherOptions()
    {
        return [
            'dataGenerator' => $this->getDataGeneratorClass(),
            'dispatcher' => $this->getDispatcherClass()
        ];
    }

    protected function getDataGeneratorClass()
    {
        return GroupCountBased::class;
    }

    protected function getDispatcherClass()
    {
        return FastRouteDispatcher::class;
    }

    /**
     * @dataProvider provideNotFoundDispatchCases
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideNotFoundDispatchCases')]
    public function testNotFoundDispatches($method, $uri, $callback)
    {
        /** @var FastRouteDispatcher $dispatcher */
        $dispatcher = simpleDispatcher($callback, $this->generateDispatcherOptions());

        $results = $dispatcher->dispatch($method, $uri);

        $this->assertSame($dispatcher::NOT_FOUND, $results[0]);
    }

    /**
     * @dataProvider provideMethodNotAllowedDispatchCases
     * @param $method
     * @param $uri
     * @param $callback
     * @param $allowedMethods
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideMethodNotAllowedDispatchCases')]
    public function testMethodNotAllowedDispatches($method, $uri, $callback, $allowedMethods)
    {
        /** @var FastRouteDispatcher $dispatcher */
        $dispatcher = simpleDispatcher($callback, $this->generateDispatcherOptions());

        $results = $dispatcher->dispatch($method, $uri);

        $this->assertSame($dispatcher::METHOD_NOT_ALLOWED, $results[0]);
    }

    /**
     * @dataProvider provideMethodNotAllowedDispatchCases
     * @param $method
     * @param $uri
     * @param $callback
     * @param $allowedMethods
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideMethodNotAllowedDispatchCases')]
    public function testGetAllowedMethods($method, $uri, $callback, $allowedMethods)
    {
        /** @var FastRouteDispatcher $dispatcher */
        $dispatcher = simpleDispatcher($callback, $this->generateDispatcherOptions());

        $results = $dispatcher->getAllowedMethods($uri);

        $this->assertSame($results, $allowedMethods);
    }

    public function testGetAllowedMethodsReusesLastUriOnly()
    {
        /** @var FastRouteDispatcher $dispatcher */
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/user', 'handler0');
            $r->addRoute('POST', '/post', 'handler1');
        }, $this->generateDispatcherOptions());

        $this->assertSame(['GET'], $dispatcher->getAllowedMethods('/user'));
        $this->assertSame(['POST'], $dispatcher->getAllowedMethods('/post'));

        // Repeating the first URI recomputes, as only the last result is kept.
        $this->assertSame(['GET'], $dispatcher->getAllowedMethods('/user'));
    }

    public function testDuplicateVariableNameError()
    {
        $this->expectException(BadRouteException::class);
        $this->expectExceptionMessage('Cannot use the same placeholder "test" twice');

        simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/foo/{test}/{test:\d+}', 'handler0');
        }, $this->generateDispatcherOptions());
    }

    public function testDuplicateVariableRoute()
    {
        $this->expectException(BadRouteException::class);
        $this->expectExceptionMessage('Cannot register two routes matching "/user/([^/]+)" for method "GET"');

        simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/user/{id}', 'handler0'); // oops, forgot \d+ restriction ;)
            $r->addRoute('GET', '/user/{name}', 'handler1');
        }, $this->generateDispatcherOptions());
    }

    public function testDuplicateStaticRoute()
    {
        $this->expectException(BadRouteException::class);
        $this->expectExceptionMessage('Cannot register two routes matching "/user" for method "GET"');

        simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/user', 'handler0');
            $r->addRoute('GET', '/user', 'handler1');
        }, $this->generateDispatcherOptions());
    }

    /**
     * @codingStandardsIgnoreStart
     * @codingStandardsIgnoreEnd
     */
    public function testShadowedStaticRoute()
    {
        $this->expectException(BadRouteException::class);
        $this->expectExceptionMessage('Static route "/user/nikic" is shadowed by previously defined variable route' .
                                      ' "/user/([^/]+)" for method "GET"');

        simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/user/{name}', 'handler0');
            $r->addRoute('GET', '/user/nikic', 'handler1');
        }, $this->generateDispatcherOptions());
    }

    public function testCapturing()
    {
        $this->expectException(BadRouteException::class);
        $this->expectExceptionMessage('Regex "(en|de)" for parameter "lang" contains a capturing group');

        simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/{lang:(en|de)}', 'handler0');
        }, $this->generateDispatcherOptions());
    }

    public static function provideFoundDispatchCases()
    {
        $cases = [];

        // 0 -------------------------------------------------------------------------------------->

        $callback = function (RouteCollector $r) {
            $r->addRoute('GET', '/resource/123/456', 'handler0');
        };

        $method = 'GET';
        $uri = '/resource/123/456';
        $handler = 'handler0';
        $argDict = [];

        $cases[] = [$method, $uri, $callback, $handler, $argDict];

        // 1 -------------------------------------------------------------------------------------->

        $callback = function (RouteCollector $r) {
            $r->addRoute('GET', '/handler0', 'handler0');
            $r->addRoute('GET', '/handler1', 'handler1');
            $r->addRoute('GET', '/handler2', 'handler2');
        };

        $method = 'GET';
        $uri = '/handler2';
        $handler = 'handler2';
        $argDict = [];

        $cases[] = [$method, $uri, $callback, $handler, $argDict];

        // 2 -------------------------------------------------------------------------------------->

        $callback = function (RouteCollector $r) {
            $r->addRoute('GET', '/user/{name}/{id:[0-9]+}', 'handler0');
            $r->addRoute('GET', '/user/{id:[0-9]+}', 'handler1');
            $r->addRoute('GET', '/user/{name}', 'handler2');
        };

        $method = 'GET';
        $uri = '/user/rdlowrey';
        $handler = 'handler2';
        $argDict = ['name' => 'rdlowrey'];

        $cases[] = [$method, $uri, $callback, $handler, $argDict];

        // 3 -------------------------------------------------------------------------------------->

        // reuse $callback from #2

        $method = 'GET';
        $uri = '/user/12345';
        $handler = 'handler1';
        $argDict = ['id' => '12345'];

        $cases[] = [$method, $uri, $callback, $handler, $argDict];

        // 4 -------------------------------------------------------------------------------------->

        // reuse $callback from #3

