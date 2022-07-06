<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Tests\TestCase;

class MethodOverrideMiddlewareTest extends TestCase
{
    public function testHeader()
    {
        $responseFactory = $this->getResponseFactory();
        $middleware = (function (Request $request, RequestHandler $handler) use ($responseFactory) {
            $this->assertSame('PUT', $request->getMethod());
            return $responseFactory->createResponse();
        })->bindTo($this);
        $methodOverrideMiddleware = new MethodOverrideMiddleware();

        $request = $this
            ->createServerRequest('/', 'POST')
            ->withHeader('X-Http-Method-Override', 'PUT');

        $middlewareDispatcher = $this->createMiddlewareDispatcher(
            $this->createMock(RequestHandler::class),
            null
        );
        $middlewareDispatcher->addCallable($middleware);
        $middlewareDispatcher->addMiddleware($methodOverrideMiddleware);
        $middlewareDispatcher->handle($request);
    }

    public function testBodyParam()
    {
        $responseFactory = $this->getResponseFactory();
        $middleware = (function (Request $request, RequestHandler $handler) use ($responseFactory) {
            $this->assertSame('PUT', $request->getMethod());
            return $responseFactory->createResponse();
        })->bindTo($this);

        $methodOverrideMiddleware = new MethodOverrideMiddleware();

        $request = $this
            ->createServerRequest('/', 'POST')
            ->withParsedBody(['_METHOD' => 'PUT']);

        $middlewareDispatcher = $this->createMiddlewareDispatcher(
            $this->createMock(RequestHandler::class),
            null
        );
        $middlewareDispatcher->addCallable($middleware);
        $middlewareDispatcher->addMiddleware($methodOverrideMiddleware);
        $middlewareDispatcher->handle($request);
    }

    public function testHeaderPreferred()
    {
        $responseFactory = $this->getResponseFactory();
        $middleware = (function (Request $request, RequestHandler $handler) use ($responseFactory) {
            $this->assertSame('DELETE', $request->getMethod());
            return $responseFactory->createResponse();
        })->bindTo($this);

        $methodOverrideMiddleware = new MethodOverrideMiddleware();

        $request = $this
            ->createServerRequest('/', 'POST')
