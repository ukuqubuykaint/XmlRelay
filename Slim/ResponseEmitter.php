<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim;

use Psr\Http\Message\ResponseInterface;

use function connection_status;
use function header;
use function headers_sent;
use function in_array;
use function min;
use function sprintf;
use function strlen;
use function strtolower;

use const CONNECTION_NORMAL;

class ResponseEmitter
{
    private int $responseChunkSize;

    public function __construct(int $responseChunkSize = 4096)
    {
        $this->responseChunkSize = $responseChunkSize;
    }

    /**
