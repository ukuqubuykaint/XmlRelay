<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Middleware;

use Error;
use InvalidArgumentException;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Handlers\ErrorHandler;
use Slim\Middleware\ErrorMiddleware;
use Slim\Middleware\RoutingMiddleware;
use Slim\Tests\Mocks\MockCustomException;
use Slim\Tests\TestCase;

class ErrorMiddlewareTest extends TestCase
{
    private function getMockLogger(): LoggerInterface
    {
        return $this->createMock(LoggerInterface::class);
    }

    public function testSetErrorHandler()
    {
        $responseFactory = $this->getResponseFactory();
        $app = new App($responseFactory);
        $callableResolver = $app->getCallableResolver();
        $logger = $this->getMockLogger();

        $routingMiddleware = new RoutingMiddleware(
            $app->getRouteResolver(),
            $app->getRouteCollector()->getRouteParser()
        );
        $app->add($routingMiddleware);

        $exception = HttpNotFoundException::class;
        $handler = (function () {
            $response = $this->createResponse(500);
            $response->getBody()->write('Oops..');
            return $response;
        })->bindTo($this);

        $errorMiddleware = new ErrorMiddleware(
            $callableResolver,
            $this->getResponseFactory(),
            false,
            false,
            false,
            $logger
        );
        $errorMiddleware->setErrorHandler($exception, $handler);
        $app->add($errorMiddleware);

        $request = $this->createServerRequest('/foo/baz/');
        $app->run($request);

        $this->expectOutputString('Oops..');
    }

    public function testSetDefaultErrorHandler()
    {
        $responseFactory = $this->getResponseFactory();
        $app = new App($responseFactory);
        $callableResolver = $app->getCallableResolver();
        $logger = $this->getMockLogger();

        $routingMiddleware = new RoutingMiddleware(
            $app->getRouteResolver(),
            $app->getRouteCollector()->getRouteParser()
        );
        $app->add($routingMiddleware);

        $handler = (function () {
            $response = $this->createResponse();
            $response->getBody()->write('Oops..');
            return $response;
        })->bindTo($this);

        $errorMiddleware = new ErrorMiddleware(
            $callableResolver,
            $this->getResponseFactory(),
            false,
            false,
            false,
            $logger
        );
        $errorMiddleware->setDefaultErrorHandler($handler);
        $app->add($errorMiddleware);

