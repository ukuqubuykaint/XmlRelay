<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Factory;

use GuzzleHttp\Psr7\ServerRequest as GuzzleServerRequest;
use Laminas\Diactoros\ServerRequest as LaminasDiactorosServerRequest;
use Nyholm\Psr7\ServerRequest as NyholmServerRequest;
use HttpSoft\Message\ServerRequest as HttpSoftServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Slim\Factory\Psr17\GuzzlePsr17Factory;
use Slim\Factory\Psr17\HttpSoftPsr17Factory;
use Slim\Factory\Psr17\LaminasDiactorosPsr17Factory;
use Slim\Factory\Psr17\NyholmPsr17Factory;
use Slim\Factory\Psr17\Psr17FactoryProvider;
use Slim\Factory\Psr17\SlimHttpServerRequestCreator;
use Slim\Factory\Psr17\SlimPsr17Factory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Http\ServerRequest;
use Slim\Interfaces\ServerRequestCreatorInterface;
use Slim\Psr7\Request as SlimServerRequest;
use Slim\Tests\TestCase;

class ServerRequestCreatorFactoryTest extends TestCase
{
    public static function provideImplementations()
    {
        return [
            [SlimPsr17Factory::class, SlimServerRequest::class],
            [HttpSoftPsr17Factory::class, HttpSoftServerRequest::class],
            [NyholmPsr17Factory::class, NyholmServerRequest::class],
            [GuzzlePsr17Factory::class, GuzzleServerRequest::class],
            [LaminasDiactorosPsr17Factory::class, LaminasDiactorosServerRequest::class],
        ];
    }
