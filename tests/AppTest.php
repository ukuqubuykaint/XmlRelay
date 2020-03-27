<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests;

use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;
use Slim\App;
use Slim\CallableResolver;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Slim\Handlers\Strategies\RequestResponseNamedArgs;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\MiddlewareDispatcherInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Middleware\BodyParsingMiddleware;
use Slim\Middleware\ErrorMiddleware;
use Slim\Middleware\RoutingMiddleware;
use Slim\MiddlewareDispatcher;
use Slim\Routing\RouteCollector;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Slim\Tests\Mocks\MockAction;
use stdClass;

use function array_key_exists;
use function array_shift;
use function count;
use function ini_set;
use function json_encode;
use function strtolower;
use function sys_get_temp_dir;
use function tempnam;

class AppTest extends TestCase
{
    public static function setupBeforeClass(): void
    {
        ini_set('error_log', tempnam(sys_get_temp_dir(), 'slim'));
    }

    public function testDoesNotUseContainerAsServiceLocator(): void
    {
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $containerProphecy = $this->prophesize(ContainerInterface::class);
        $app = new App($responseFactoryProphecy->reveal(), $containerProphecy->reveal());

        $containerProphecy->has(Argument::type('string'))->shouldNotHaveBeenCalled();
        $containerProphecy->get(Argument::type('string'))->shouldNotHaveBeenCalled();
    }

    /********************************************************************************
     * Getter methods
     *******************************************************************************/

    public function testGetContainer(): void
    {
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $containerProphecy = $this->prophesize(ContainerInterface::class);
        $app = new App($responseFactoryProphecy->reveal(), $containerProphecy->reveal());

        $this->assertSame($containerProphecy->reveal(), $app->getContainer());
    }

    public function testGetCallableResolverReturnsInjectedInstance(): void
    {
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $callableResolverProphecy = $this->prophesize(CallableResolverInterface::class);
        $app = new App($responseFactoryProphecy->reveal(), null, $callableResolverProphecy->reveal());

        $this->assertSame($callableResolverProphecy->reveal(), $app->getCallableResolver());
    }

    public function testCreatesCallableResolverWhenNull(): void
    {
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $containerProphecy = $this->prophesize(ContainerInterface::class);
        $callableResolver = new CallableResolver($containerProphecy->reveal());
        $app = new App($responseFactoryProphecy->reveal(), $containerProphecy->reveal(), null);

        $this->assertEquals($callableResolver, $app->getCallableResolver());
    }

    public function testGetRouteCollectorReturnsInjectedInstance(): void
    {
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $routeCollectorProphecy = $this->prophesize(RouteCollectorInterface::class);
        $routeParserProphecy = $this->prophesize(RouteParserInterface::class);

        $routeCollectorProphecy->getRouteParser()->willReturn($routeParserProphecy->reveal());

        $app = new App($responseFactoryProphecy->reveal(), null, null, $routeCollectorProphecy->reveal());

        $this->assertSame($routeCollectorProphecy->reveal(), $app->getRouteCollector());
    }

    public function testCreatesRouteCollectorWhenNullWithInjectedContainer(): void
    {
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $containerProphecy = $this->prophesize(ContainerInterface::class);
        $callableResolverProphecy = $this->prophesize(CallableResolverInterface::class);
        $routeCollector = new RouteCollector(
            $responseFactoryProphecy->reveal(),
            $callableResolverProphecy->reveal(),
            $containerProphecy->reveal()
        );
        $app = new App(
            $responseFactoryProphecy->reveal(),
            $containerProphecy->reveal(),
            $callableResolverProphecy->reveal()
        );

        $this->assertEquals($routeCollector, $app->getRouteCollector());
    }

    public function testGetMiddlewareDispatcherGetsSeededAndReturnsInjectedInstance(): void
    {
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);

        $middlewareDispatcherProphecy = $this->prophesize(MiddlewareDispatcherInterface::class);
        $middlewareDispatcherProphecy
            ->seedMiddlewareStack(Argument::any())
            ->shouldBeCalledOnce();

        $app = new App(
            $responseFactoryProphecy->reveal(),
            null,
            null,
            null,
            null,
            $middlewareDispatcherProphecy->reveal()
        );

        $this->assertSame($middlewareDispatcherProphecy->reveal(), $app->getMiddlewareDispatcher());
    }

    public static function lowerCaseRequestMethodsProvider(): array
    {
        return [
            ['get'],
            ['post'],
            ['put'],
            ['patch'],
            ['delete'],
            ['options'],
        ];
    }

    /**
     * @param string $method
     * @dataProvider upperCaseRequestMethodsProvider()
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('upperCaseRequestMethodsProvider')]
    public function testGetPostPutPatchDeleteOptionsMethods(string $method): void
    {
        $streamProphecy = $this->prophesize(StreamInterface::class);
        $streamProphecy->__toString()->willReturn('Hello World');

        $responseProphecy = $this->prophesize(ResponseInterface::class);
        $responseProphecy->getBody()->willReturn($streamProphecy->reveal());

        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $responseFactoryProphecy->createResponse()->willReturn($responseProphecy->reveal());

        $uriProphecy = $this->prophesize(UriInterface::class);
        $uriProphecy->getPath()->willReturn('/');

        $requestProphecy = $this->prophesize(ServerRequestInterface::class);
        $requestProphecy->getMethod()->willReturn($method);
        $requestProphecy->getUri()->willReturn($uriProphecy->reveal());
        $requestProphecy->getAttribute(RouteContext::ROUTING_RESULTS)->willReturn(null);
        $requestProphecy->withAttribute(Argument::type('string'), Argument::any())->will(function ($args) {
            $this->getAttribute($args[0])->willReturn($args[1]);
            return $this;
        });

        $methodName = strtolower($method);
        $app = new App($responseFactoryProphecy->reveal());
        $app->$methodName('/', function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response;
        });
        $response = $app->handle($requestProphecy->reveal());

        $this->assertSame('Hello World', (string) $response->getBody());
    }

    public function testAnyRoute(): void
    {
        $streamProphecy = $this->prophesize(StreamInterface::class);
        $streamProphecy->__toString()->willReturn('Hello World');

        $responseProphecy = $this->prophesize(ResponseInterface::class);
        $responseProphecy->getBody()->willReturn($streamProphecy->reveal());

        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $responseFactoryProphecy->createResponse()->willReturn($responseProphecy->reveal());

        $app = new App($responseFactoryProphecy->reveal());
        $app->any('/', function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response;
        });

        foreach ($this->upperCaseRequestMethodsProvider() as $methods) {
            $method = $methods[0];
            $uriProphecy = $this->prophesize(UriInterface::class);
            $uriProphecy->getPath()->willReturn('/');

            $requestProphecy = $this->prophesize(ServerRequestInterface::class);
            $requestProphecy->getMethod()->willReturn($method);
            $requestProphecy->getUri()->willReturn($uriProphecy->reveal());
            $requestProphecy->getAttribute(RouteContext::ROUTING_RESULTS)->willReturn(null);
            $requestProphecy->withAttribute(Argument::type('string'), Argument::any())->will(function ($args) {
                $this->getAttribute($args[0])->willReturn($args[1]);
                return $this;
            });

            $response = $app->handle($requestProphecy->reveal());

            $this->assertSame('Hello World', (string) $response->getBody());
        }
    }

    /********************************************************************************
     * Route collector proxy methods
     *******************************************************************************/

    public static function upperCaseRequestMethodsProvider(): array
    {
        return [
            ['GET'],
            ['POST'],
            ['PUT'],
            ['PATCH'],
            ['DELETE'],
            ['OPTIONS'],
        ];
    }

    /**
     * @param string $method
     * @dataProvider lowerCaseRequestMethodsProvider
     * @dataProvider upperCaseRequestMethodsProvider
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('lowerCaseRequestMethodsProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('upperCaseRequestMethodsProvider')]
    public function testMapRoute(string $method): void
    {
        $streamProphecy = $this->prophesize(StreamInterface::class);
        $streamProphecy->__toString()->willReturn('Hello World');

        $responseProphecy = $this->prophesize(ResponseInterface::class);
        $responseProphecy->getBody()->willReturn($streamProphecy->reveal());

        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $responseFactoryProphecy->createResponse()->willReturn($responseProphecy->reveal());

        $uriProphecy = $this->prophesize(UriInterface::class);
        $uriProphecy->getPath()->willReturn('/');

        $requestProphecy = $this->prophesize(ServerRequestInterface::class);
        $requestProphecy->getMethod()->willReturn($method);
        $requestProphecy->getUri()->willReturn($uriProphecy->reveal());
        $requestProphecy->getAttribute(RouteContext::ROUTING_RESULTS)->willReturn(null);
        $requestProphecy->withAttribute(Argument::type('string'), Argument::any())->will(function ($args) {
            $this->getAttribute($args[0])->willReturn($args[1]);
            return $this;
        });

        $app = new App($responseFactoryProphecy->reveal());
        $app->map([$method], '/', function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response;
        });
        $response = $app->handle($requestProphecy->reveal());

        $this->assertSame('Hello World', (string) $response->getBody());
    }

    public function testRedirectRoute(): void
    {
        $from = '/from';
        $to = '/to';

        $routeCreatedResponse = $this->prophesize(ResponseInterface::class);

        $handlerCreatedResponse = $this->prophesize(ResponseInterface::class);
        $handlerCreatedResponse->getStatusCode()->willReturn(301);
        $handlerCreatedResponse->getHeaderLine('Location')->willReturn($to);
        $handlerCreatedResponse->withHeader(
            Argument::type('string'),
            Argument::type('string')
        )->will(function ($args) {
            $this->getHeader($args[0])->willReturn($args[1]);
            return $this;
        });

        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $responseFactoryProphecy->createResponse()->willReturn($routeCreatedResponse->reveal());
        $responseFactoryProphecy->createResponse(301)->willReturn($handlerCreatedResponse->reveal());

        $uriProphecy = $this->prophesize(UriInterface::class);
        $uriProphecy->getPath()->willReturn($from);
        $uriProphecy->__toString()->willReturn($to);

        $requestProphecy = $this->prophesize(ServerRequestInterface::class);
        $requestProphecy->getMethod()->willReturn('GET');
        $requestProphecy->getUri()->willReturn($uriProphecy->reveal());
        $requestProphecy->getAttribute(RouteContext::ROUTING_RESULTS)->willReturn(null);
        $requestProphecy->withAttribute(Argument::type('string'), Argument::any())->will(function ($args) {
            $this->getAttribute($args[0])->willReturn($args[1]);
            return $this;
        });

        $app = new App($responseFactoryProphecy->reveal());
        $app->redirect($from, $to, 301);
        $response = $app->handle($requestProphecy->reveal());

        $responseFactoryProphecy->createResponse(301)->shouldHaveBeenCalled();
        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame($to, $response->getHeaderLine('Location'));
    }

    public function testRouteWithInternationalCharacters(): void
    {
        $path = '/новости';

        $streamProphecy = $this->prophesize(StreamInterface::class);
        $streamProphecy->__toString()->willReturn('Hello World');

        $responseProphecy = $this->prophesize(ResponseInterface::class);
        $responseProphecy->getBody()->willReturn($streamProphecy->reveal());

        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $responseFactoryProphecy->createResponse()->willReturn($responseProphecy->reveal());

        $app = new App($responseFactoryProphecy->reveal());
        $app->get($path, function () use ($responseProphecy) {
            return $responseProphecy->reveal();
        });

        $uriProphecy = $this->prophesize(UriInterface::class);
        $uriProphecy->getPath()->willReturn($path);

        $requestProphecy = $this->prophesize(ServerRequestInterface::class);
        $requestProphecy->getMethod()->willReturn('GET');
        $requestProphecy->getUri()->willReturn($uriProphecy->reveal());
        $requestProphecy->getAttribute(RouteContext::ROUTING_RESULTS)->willReturn(null);
        $requestProphecy->withAttribute(Argument::type('string'), Argument::any())->will(function ($args) {
            $this->getAttribute($args[0])->willReturn($args[1]);
            return $this;
        });
        $response = $app->handle($requestProphecy->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame('Hello World', (string) $response->getBody());
    }

    /********************************************************************************
     * Route Patterns
     *******************************************************************************/

    public static function routePatternsProvider(): array
    {
        return [
            [''], // Empty Route
            ['/'], // Single Slash Route
            ['foo'], // Route That Does Not Start With A Slash
            ['/foo'], // Route That Does Not End In A Slash
            ['/foo/'], // Route That Ends In A Slash
        ];
    }

    /**
     * @param string $pattern
     * @dataProvider routePatternsProvider
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('routePatternsProvider')]
    public function testRoutePatterns(string $pattern): void
    {
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);

        $app = new App($responseFactoryProphecy->reveal());
        $app->get($pattern, function () {
        });

        $routeCollector = $app->getRouteCollector();
        $route = $routeCollector->lookupRoute('route0');

        $this->assertSame($pattern, $route->getPattern());
    }

    /********************************************************************************
     * Route Groups
     *******************************************************************************/

    public static function routeGroupsDataProvider(): array
    {
        return [
            'empty group with empty route' => [
                ['', ''], ''
            ],
            'empty group with single slash route' => [
                ['', '/'], '/'
            ],
            'empty group with segment route that does not end in aSlash' => [
                ['', '/foo'], '/foo'
            ],
            'empty group with segment route that ends in aSlash' => [
                ['', '/foo/'], '/foo/'
            ],
            'group single slash with empty route' => [
                ['/', ''], '/'
            ],
            'group single slash with single slash route' => [
                ['/', '/'], '//'
            ],
            'group single slash with segment route that does not end in aSlash' => [
                ['/', '/foo'], '//foo'
            ],
            'group single slash with segment route that ends in aSlash' => [
                ['/', '/foo/'], '//foo/'
            ],
            'group segment with empty route' => [
                ['/foo', ''], '/foo'
            ],
            'group segment with single slash route' => [
                ['/foo', '/'], '/foo/'
            ],
            'group segment with segment route that does not end in aSlash' => [
                ['/foo', '/bar'], '/foo/bar'
            ],
            'group segment with segment route that ends in aSlash' => [
                ['/foo', '/bar/'], '/foo/bar/'
            ],
            'empty group with nested group segment with an empty route' => [
                ['', '/foo', ''], '/foo'
            ],
            'empty group with nested group segment with single slash route' => [
                ['', '/foo', '/'], '/foo/'
            ],
            'group single slash with empty nested group and segment route without leading slash' => [
                ['/', '', 'foo'], '/foo'
            ],
            'group single slash with empty nested group and segment route' => [
                ['/', '', '/foo'], '//foo'
            ],
            'group single slash with single slash group and segment route without leading slash' => [
                ['/', '/', 'foo'], '//foo'
            ],
            'group single slash with single slash nested group and segment route' => [
                ['/', '/', '/foo'], '///foo'
            ],
            'group single slash with nested group segment with an empty route' => [
                ['/', '/foo', ''], '//foo'
            ],
            'group single slash with nested group segment with single slash route' => [
                ['/', '/foo', '/'], '//foo/'
            ],
            'group single slash with nested group segment with segment route' => [
                ['/', '/foo', '/bar'], '//foo/bar'
            ],
            'group single slash with nested group segment with segment route that has aTrailing slash' => [
                ['/', '/foo', '/bar/'], '//foo/bar/'
            ],
            'empty group with empty nested group and segment route without leading slash' => [
                ['', '', 'foo'], 'foo'
            ],
            'empty group with empty nested group and segment route' => [
                ['', '', '/foo'], '/foo'
            ],
            'empty group with single slash group and segment route without leading slash' => [
                ['', '/', 'foo'], '/foo'
            ],
            'empty group with single slash nested group and segment route' => [
                ['', '/', '/foo'], '//foo'
            ],
            'empty group with nested group segment with segment route' => [
                ['', '/foo', '/bar'], '/foo/bar'
            ],
            'empty group with nested group segment with segment route that has aTrailing slash' => [
                ['', '/foo', '/bar/'], '/foo/bar/'
            ],
            'group segment with empty nested group and segment route without leading slash' => [
                ['/foo', '', 'bar'], '/foobar'
            ],
            'group segment with empty nested group and segment route' => [
                ['/foo', '', '/bar'], '/foo/bar'
            ],
            'group segment with single slash nested group and segment route' => [
                ['/foo', '/', 'bar'], '/foo/bar'
            ],
            'group segment with single slash nested group and slash segment route' => [
                ['/foo', '/', '/bar'], '/foo//bar'
            ],
            'two group segments with empty route' => [
                ['/foo', '/bar', ''], '/foo/bar'
            ],
            'two group segments with single slash route' => [
                ['/foo', '/bar', '/'], '/foo/bar/'
            ],
            'two group segments with segment route' => [
                ['/foo', '/bar', '/baz'], '/foo/bar/baz'
            ],
            'two group segments with segment route that has aTrailing slash' => [
                ['/foo', '/bar', '/baz/'], '/foo/bar/baz/'
            ],
        ];
    }

    public function testGroupClosureIsBoundToThisClass(): void
    {
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $app = new App($responseFactoryProphecy->reveal());

        $testCase = $this;
        $app->group('/foo', function () use ($testCase) {
            $testCase->assertSame($testCase, $this);
        });
    }

    /**
     * @dataProvider routeGroupsDataProvider
     * @param array  $sequence
     * @param string $expectedPath
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('routeGroupsDataProvider')]
    public function testRouteGroupCombinations(array $sequence, string $expectedPath): void
    {
        $responseFactoryProphecy = $this->prophesize(ResponseFactoryInterface::class);
        $app = new App($responseFactoryProphecy->reveal());

        $processSequence = function (RouteCollectorProxy $app, array $sequence, $processSequence) {
            $path = array_shift($sequence);

