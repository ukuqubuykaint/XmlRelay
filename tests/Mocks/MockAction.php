<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Mocks;

use InvalidArgumentException;

use function compact;
use function count;
use function json_encode;

class MockAction
{
    public function __call($name, array $arguments)
    {
        if (count($arguments) !== 3) {
            throw new InvalidArgumentException("Not a Slim call");
        }

