<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Factory\Psr17;

use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Slim\Interfaces\ServerRequestCreatorInterface;

use function class_exists;

class SlimHttpServerRequestCreator implements ServerRequestCreatorInterface
{
    protected ServerRequestCreatorInterface $serverRequestCreator;
