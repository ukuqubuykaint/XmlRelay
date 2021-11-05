<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Factory;

use GuzzleHttp\Psr7\HttpFactory;
use Laminas\Diactoros\ResponseFactory as LaminasDiactorosResponseFactory;
use HttpSoft\Message\ResponseFactory as HttpSoftResponseFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;
use Slim\Factory\AppFactory;
use Slim\Factory\Psr17\GuzzlePsr17Factory;
use Slim\Factory\Psr17\HttpSoftPsr17Factory;
use Slim\Factory\Psr17\LaminasDiactorosPsr17Factory;
use Slim\Factory\Psr17\NyholmPsr17Factory;
use Slim\Factory\Psr17\Psr17FactoryProvider;
use Slim\Factory\Psr17\SlimHttpPsr17Factory;
use Slim\Factory\Psr17\SlimPsr17Factory;
use Slim\Http\Factory\DecoratedResponseFactory;
use Slim\Http\Response as DecoratedResponse;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\MiddlewareDispatcherInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Interfaces\RouteResolverInterface;
use Slim\Psr7\Factory\ResponseFactory as SlimResponseFactory;
use Slim\Routing\RouteCollector;
use Slim\Tests\Mocks\MockPsr17FactoryWithoutStreamFactory;
use Slim\Tests\TestCase;
use stdClass;

class AppFactoryTest extends TestCase
{
    protected function tearDown(): void
    {
        $reflectionClass = new ReflectionClass(SlimHttpPsr17Factory::class);
        $reflectionClass->setStaticPropertyValue('responseFactoryClass', DecoratedResponseFactory::class);
    }

    public static function provideImplementations()
    {
        return [
            [SlimPsr17Factory::class, SlimResponseFactory::class],
            [HttpSoftPsr17Factory::class, HttpSoftResponseFactory::class],
            [NyholmPsr17Factory::class, Psr17Factory::class],
            [GuzzlePsr17Factory::class, HttpFactory::class],
            [LaminasDiactorosPsr17Factory::class, LaminasDiactorosResponseFactory::class],
        ];
    }

    /**
     * @dataProvider provideImplementations
     * @param string $psr17factory
     * @param string $expectedResponseFactoryClass
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideImplementations')]
    public function testCreateAppWithAllImplementations(string $psr17factory, string $expectedResponseFactoryClass)
    {
        Psr17FactoryProvider::setFactories([$psr17factory]);
        AppFactory::setSlimHttpDecoratorsAutomaticDetection(false);

        $app = AppFactory::create();

        $routeCollector = $app->getRouteCollector();

        $responseFactoryProperty = new ReflectionProperty(RouteCollector::class, 'responseFactory');
        $this->setAccessible($responseFactoryProperty);

        $responseFactory = $responseFactoryProperty->getValue($routeCollector);

        $this->assertInstanceOf($expectedResponseFactoryClass, $responseFactory);
    }

    public function testDetermineResponseFactoryReturnsDecoratedFactory()
    {
        Psr17FactoryProvider::setFactories([SlimPsr17Factory::class]);
        AppFactory::setSlimHttpDecoratorsAutomaticDetection(true);

        $app = AppFactory::create();

        $this->assertInstanceOf(DecoratedResponseFactory::class, $app->getResponseFactory());
    }

    public function testDetermineResponseFactoryThrowsRuntimeExceptionIfDecoratedNotInstanceOfResponseInterface()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Slim\\Factory\\Psr17\\SlimHttpPsr17Factory could not instantiate a decorated response factory.'
        );

        $reflectionClass = new ReflectionClass(SlimHttpPsr17Factory::class);
        $reflectionClass->setStaticPropertyValue('responseFactoryClass', SlimHttpPsr17Factory::class);

