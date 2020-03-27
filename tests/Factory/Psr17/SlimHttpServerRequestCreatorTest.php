<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Factory\Psr17;

use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;
use Slim\Factory\Psr17\SlimHttpServerRequestCreator;
use Slim\Http\ServerRequest;
use Slim\Interfaces\ServerRequestCreatorInterface;
use Slim\Tests\TestCase;
use stdClass;

class SlimHttpServerRequestCreatorTest extends TestCase
{
    /**
     * We need to reset the static property of SlimHttpServerRequestCreator back to its original value
     * Otherwise other tests will fail
     */
    public function tearDown(): void
    {
        $serverRequestCreatorProphecy = $this->prophesize(ServerRequestCreatorInterface::class);

        $slimHttpServerRequestCreator = new SlimHttpServerRequestCreator($serverRequestCreatorProphecy->reveal());

        $serverRequestDecoratorClassProperty = new ReflectionProperty(
            SlimHttpServerRequestCreator::class,
            'serverRequestDecoratorClass'
        );
        $this->setAccessible($serverRequestDecoratorClassProperty);
        $serverRequestDecoratorClassProperty->setValue($slimHttpServerRequestCreator, ServerRequest::class);
    }

    public function testCreateServerRequestFromGlobals()
    {
