<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use Slim\Middleware\BodyParsingMiddleware;
use Slim\Tests\TestCase;

use function is_string;
use function simplexml_load_string;

class BodyParsingMiddlewareTest extends TestCase
{
    /**
     * Create a request handler that simply assigns the $request that it receives to a public property
     * of the returned response, so that we can then inspect that request.
     */
    protected function createRequestHandler(): RequestHandlerInterface
    {
        $response = $this->createResponse();
        return new class ($response) implements RequestHandlerInterface {
            private $response;
            public $request;

            public function __construct(ResponseInterface $response)
            {
                $this->response = $response;
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $this->request = $request;
                return $this->response;
            }
        };
    }

    /**
     * @param string $contentType
     * @param string $body
     * @return ServerRequestInterface
     */
    protected function createRequestWithBody($contentType, $body)
    {
        $request = $this->createServerRequest('/', 'POST');
        if (is_string($contentType)) {
            $request = $request->withHeader('Content-Type', $contentType);
        }
        if (is_string($body)) {
            $request = $request->withBody($this->createStream($body));
        }
        return $request;
    }


    public static function parsingProvider()
    {
        return [
            'form' => [
                'application/x-www-form-urlencoded;charset=utf8',
                'foo=bar',
                ['foo' => 'bar'],
            ],
            'json' => [
                "application/json",
                '{"foo":"bar"}',
                ['foo' => 'bar'],
            ],
            'json-with-charset' => [
                "application/json\t ; charset=utf8",
                '{"foo":"bar"}',
                ['foo' => 'bar'],
            ],
            'json-suffix' => [
                'application/vnd.api+json;charset=utf8',
                '{"foo":"bar"}',
                ['foo' => 'bar'],
            ],
            'xml' => [
                'application/xml',
                '<person><name>John</name></person>',
                simplexml_load_string('<person><name>John</name></person>'),
            ],
            'xml-suffix' => [
                'application/hal+xml;charset=utf8',
                '<person><name>John</name></person>',
                simplexml_load_string('<person><name>John</name></person>'),
            ],
            'text-xml' => [
                'text/xml',
                '<person><name>John</name></person>',
                simplexml_load_string('<person><name>John</name></person>'),
            ],
            'invalid-json' => [
                'application/json;charset=utf8',
                '{"foo"}/bar',
                null,
            ],
            'valid-json-but-not-an-array' => [
                'application/json;charset=utf8',
                '"foo bar"',
                null,
            ],
            'unknown-contenttype' => [
                'text/foo+bar',
