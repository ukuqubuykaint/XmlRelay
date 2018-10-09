<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Mocks;

use Exception;
use Psr\Http\Message\StreamInterface;

use function min;
use function str_repeat;

use const SEEK_SET;

class SmallChunksStream implements StreamInterface
{
    public const CHUNK_SIZE = 10;
    public const SIZE = 40;

    /**
     * @var int
     */
    private $amountToRead;

    public function __construct()
    {
        $this->amountToRead = self::SIZE;
    }

    public function __toString(): string
    {
        return str_repeat('.', self::SIZE);
    }

    public function close(): void
    {
    }

    public function detach()
    {
        throw new Exception('not implemented');
    }

    public function eof(): bool
    {
