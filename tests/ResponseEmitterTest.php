<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests;

use ReflectionClass;
use Slim\ResponseEmitter;
use Slim\Tests\Assets\HeaderStack;
use Slim\Tests\Mocks\MockStream;
use Slim\Tests\Mocks\SlowPokeStream;
use Slim\Tests\Mocks\SmallChunksStream;

use function base64_decode;
use function fopen;
use function fwrite;
use function in_array;
use function popen;
use function rewind;
use function str_repeat;
use function stream_filter_append;
use function stream_filter_remove;
use function stream_get_filters;
use function strlen;
use function trim;

use const CONNECTION_ABORTED;
use const CONNECTION_TIMEOUT;
use const STREAM_FILTER_READ;
use const STREAM_FILTER_WRITE;

class ResponseEmitterTest extends TestCase
{
    public function setUp(): void
    {
        HeaderStack::reset();
    }

    public function tearDown(): void
    {
        HeaderStack::reset();
    }

    public function testRespond(): void
    {
        $response = $this->createResponse();
        $response->getBody()->write('Hello');

        $responseEmitter = new ResponseEmitter();
        $responseEmitter->emit($response);

        $this->expectOutputString('Hello');
    }

    public function testRespondWithPaddedStreamFilterOutput(): void
    {
        $availableFilter = stream_get_filters();

        $filterName = 'string.rot13';
        $unfilterName = 'string.rot13';
        $specificFilterName = 'string.rot13';
