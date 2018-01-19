<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Factory\Psr17;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use RuntimeException;
use Slim\Interfaces\Psr17FactoryInterface;
use Slim\Interfaces\ServerRequestCreatorInterface;

use function class_exists;
use function get_called_class;

abstract class Psr17Factory implements Psr17FactoryInterface
{
    protected static string $responseFactoryClass;

    protected static string $streamFactoryClass;

    protected static string $serverRequestCreatorClass;

    protected static string $serverRequestCreatorMethod;

    /**
     * {@inheritdoc}
     */
    public static function getResponseFactory(): ResponseFactoryInterface
    {
        if (
            !static::isResponseFactoryAvailable()
            || !(($responseFactory = new static::$responseFactoryClass()) instanceof ResponseFactoryInterface)
        ) {
            throw new RuntimeException(get_called_class() . ' could not instantiate a response factory.');
        }

        return $responseFactory;
    }

