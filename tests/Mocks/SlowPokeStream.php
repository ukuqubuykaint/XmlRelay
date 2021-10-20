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
use function usleep;

use const SEEK_SET;

class SlowPokeStream implements StreamInterface
{
    public const CHUNK_SIZE = 1;
    public const SIZE = 500;

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
        $content = '';
        while (!$this->eof()) {
