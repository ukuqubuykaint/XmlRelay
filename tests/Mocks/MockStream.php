<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Mocks;

use Exception;
use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

use function clearstatcache;
use function fclose;
use function feof;
use function fopen;
use function fread;
use function fseek;
use function fstat;
use function ftell;
use function fwrite;
use function gettype;
use function is_resource;
use function is_string;
use function stream_get_contents;
use function stream_get_meta_data;
use function var_export;

use const SEEK_SET;

class MockStream implements StreamInterface
{
    /** @var resource A resource reference */
    private $stream;

    /** @var bool */
    private $seekable;

    /** @var bool */
    private $readable;

    /** @var bool */
    private $writable;

    /** @var array|mixed|null|void */
    private $uri;

    /** @var int|null */
