<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use Slim\CallableResolver;
use Slim\Tests\Mocks\CallableTest;
use Slim\Tests\Mocks\InvokableTest;
use Slim\Tests\Mocks\MiddlewareTest;
use Slim\Tests\Mocks\RequestHandlerTest;

class CallableResolverTest extends TestCase
{
    private ObjectProphecy $containerProphecy;

    public static function setUpBeforeClass(): void
    {
        function testAdvancedCallable()
        {
            return true;
        }
    }

    public function setUp(): void
    {
        CallableTest::$CalledCount = 0;
        InvokableTest::$CalledCount = 0;
        RequestHandlerTest::$CalledCount = 0;

        $this->containerProphecy = $this->prophesize(ContainerInterface::class);
        $this->containerProphecy->has(Argument::type('string'))->willReturn(false);
    }

    public function testClosure(): void
    {
        $test = function () {
            return true;
        };
        $resolver = new CallableResolver(); // No container injected
        $callable = $resolver->resolve($test);
        $callableRoute = $resolver->resolveRoute($test);
        $callableMiddleware = $resolver->resolveMiddleware($test);

        $this->assertTrue($callable());
        $this->assertTrue($callableRoute());
        $this->assertTrue($callableMiddleware());
    }

    public function testClosureContainer(): void
    {
        $this->containerProphecy->has('ultimateAnswer')->willReturn(true);
        $this->containerProphecy->get('ultimateAnswer')->willReturn(42);

        $that = $this;
        $test = function () use ($that) {
            $that->assertInstanceOf(ContainerInterface::class, $this);

            /** @var ContainerInterface $this */
            return $this->get('ultimateAnswer');
        };

        /** @var ContainerInterface $container */
        $container = $this->containerProphecy->reveal();
        $resolver = new CallableResolver($container);
        $callable = $resolver->resolve($test);
        $callableRoute = $resolver->resolveRoute($test);
        $callableMiddleware = $resolver->resolveMiddleware($test);

        $this->assertSame(42, $callable());
        $this->assertSame(42, $callableRoute());
        $this->assertSame(42, $callableMiddleware());
    }

    public function testFunctionName(): void
    {
        $resolver = new CallableResolver(); // No container injected
        $callable = $resolver->resolve(__NAMESPACE__ . '\testAdvancedCallable');
        $callableRoute = $resolver->resolveRoute(__NAMESPACE__ . '\testAdvancedCallable');
        $callableMiddleware = $resolver->resolveMiddleware(__NAMESPACE__ . '\testAdvancedCallable');

        $this->assertTrue($callable());
        $this->assertTrue($callableRoute());
        $this->assertTrue($callableMiddleware());
    }

    public function testObjMethodArray(): void
    {
        $obj = new CallableTest();
        $resolver = new CallableResolver(); // No container injected
        $callable = $resolver->resolve([$obj, 'toCall']);
        $callableRoute = $resolver->resolveRoute([$obj, 'toCall']);
        $callableMiddleware = $resolver->resolveMiddleware([$obj, 'toCall']);

        $callable();
        $this->assertSame(1, CallableTest::$CalledCount);

        $callableRoute();
        $this->assertSame(2, CallableTest::$CalledCount);

        $callableMiddleware();
        $this->assertSame(3, CallableTest::$CalledCount);
    }

    public function testSlimCallable(): void
    {
        $resolver = new CallableResolver(); // No container injected
        $callable = $resolver->resolve('Slim\Tests\Mocks\CallableTest:toCall');
        $callableRoute = $resolver->resolveRoute('Slim\Tests\Mocks\CallableTest:toCall');
        $callableMiddleware = $resolver->resolveMiddleware('Slim\Tests\Mocks\CallableTest:toCall');

        $callable();
        $this->assertSame(1, CallableTest::$CalledCount);

        $callableRoute();
        $this->assertSame(2, CallableTest::$CalledCount);

        $callableMiddleware();
        $this->assertSame(3, CallableTest::$CalledCount);
    }

    public function testSlimCallableAsArray(): void
    {
        $resolver = new CallableResolver(); // No container injected
        $callable = $resolver->resolve([CallableTest::class, 'toCall']);
        $callableRoute = $resolver->resolveRoute([CallableTest::class, 'toCall']);
        $callableMiddleware = $resolver->resolveMiddleware([CallableTest::class, 'toCall']);

        $callable();
        $this->assertSame(1, CallableTest::$CalledCount);

        $callableRoute();
        $this->assertSame(2, CallableTest::$CalledCount);

        $callableMiddleware();
        $this->assertSame(3, CallableTest::$CalledCount);
    }

    public function testSlimCallableContainer(): void
    {
        /** @var ContainerInterface $container */
        $container = $this->containerProphecy->reveal();
        $resolver = new CallableResolver($container);
        $resolver->resolve('Slim\Tests\Mocks\CallableTest:toCall');
        $this->assertSame($container, CallableTest::$CalledContainer);

        CallableTest::$CalledContainer = null;
        $resolver->resolveRoute('Slim\Tests\Mocks\CallableTest:toCall');
        $this->assertSame($container, CallableTest::$CalledContainer);

        CallableTest::$CalledContainer = null;
        $resolver->resolveMiddleware('Slim\Tests\Mocks\CallableTest:toCall');
        $this->assertSame($container, CallableTest::$CalledContainer);
    }

    public function testSlimCallableAsArrayContainer(): void
    {
        /** @var ContainerInterface $container */
        $container = $this->containerProphecy->reveal();
        $resolver = new CallableResolver($container);
        $resolver->resolve([CallableTest::class, 'toCall']);
        $this->assertSame($container, CallableTest::$CalledContainer);

        CallableTest::$CalledContainer = null;
        $resolver->resolveRoute([CallableTest::class, 'toCall']);
        $this->assertSame($container, CallableTest::$CalledContainer);

        CallableTest::$CalledContainer = null;
        $resolver->resolveMiddleware([CallableTest::class ,'toCall']);
        $this->assertSame($container, CallableTest::$CalledContainer);
    }

    public function testContainer(): void
    {
        $this->containerProphecy->has('callable_service')->willReturn(true);
        $this->containerProphecy->get('callable_service')->willReturn(new CallableTest());

        /** @var ContainerInterface $container */
        $container = $this->containerProphecy->reveal();

        $resolver = new CallableResolver($container);
        $callable = $resolver->resolve('callable_service:toCall');
        $callableRoute = $resolver->resolveRoute('callable_service:toCall');
        $callableMiddleware = $resolver->resolveMiddleware('callable_service:toCall');

        $callable();
        $this->assertSame(1, CallableTest::$CalledCount);

        $callableRoute();
        $this->assertSame(2, CallableTest::$CalledCount);

        $callableMiddleware();
        $this->assertSame(3, CallableTest::$CalledCount);
    }

    public function testResolutionToAnInvokableClassInContainer(): void
    {
        $this->containerProphecy->has('an_invokable')->willReturn(true);
        $this->containerProphecy->get('an_invokable')->willReturn(new InvokableTest());

        /** @var ContainerInterface $container */
        $container = $this->containerProphecy->reveal();

        $resolver = new CallableResolver($container);
        $callable = $resolver->resolve('an_invokable');
        $callableRoute = $resolver->resolveRoute('an_invokable');
        $callableMiddleware = $resolver->resolveMiddleware('an_invokable');

        $callable();
        $this->assertSame(1, InvokableTest::$CalledCount);

        $callableRoute();
        $this->assertSame(2, InvokableTest::$CalledCount);

        $callableMiddleware();
        $this->assertSame(3, InvokableTest::$CalledCount);
    }

    public function testResolutionToAnInvokableClass(): void
    {
        $resolver = new CallableResolver(); // No container injected
        $callable = $resolver->resolve('Slim\Tests\Mocks\InvokableTest');
        $callableRoute = $resolver->resolveRoute('Slim\Tests\Mocks\InvokableTest');
        $callableMiddleware = $resolver->resolveMiddleware('Slim\Tests\Mocks\InvokableTest');

        $callable();
        $this->assertSame(1, InvokableTest::$CalledCount);

        $callableRoute();
        $this->assertSame(2, InvokableTest::$CalledCount);

        $callableMiddleware();
        $this->assertSame(3, InvokableTest::$CalledCount);
    }

    public function testResolutionToAPsrRequestHandlerClass(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Slim\\Tests\\Mocks\\RequestHandlerTest is not resolvable');

        $resolver = new CallableResolver(); // No container injected
        $resolver->resolve(RequestHandlerTest::class);
    }

    public function testRouteResolutionToAPsrRequestHandlerClass(): void
    {
        $request = $this->createServerRequest('/', 'GET');
        $resolver = new CallableResolver(); // No container injected
        $callableRoute = $resolver->resolveRoute(RequestHandlerTest::class);
        $callableRoute($request);
        $this->assertSame(1, RequestHandlerTest::$CalledCount);
    }

    public function testMiddlewareResolutionToAPsrRequestHandlerClass(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Slim\\Tests\\Mocks\\RequestHandlerTest is not resolvable');

        $resolver = new CallableResolver(); // No container injected
        $resolver->resolveMiddleware(RequestHandlerTest::class);
    }

    public function testObjPsrRequestHandlerClass(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('{} is not resolvable');

        $obj = new RequestHandlerTest();
        $resolver = new CallableResolver(); // No container injected
        $resolver->resolve($obj);
    }

    public function testRouteObjPsrRequestHandlerClass(): void
    {
        $obj = new RequestHandlerTest();
        $request = $this->createServerRequest('/', 'GET');
        $resolver = new CallableResolver(); // No container injected
        $callableRoute = $resolver->resolveRoute($obj);
        $callableRoute($request);
        $this->assertSame(1, RequestHandlerTest::$CalledCount);
