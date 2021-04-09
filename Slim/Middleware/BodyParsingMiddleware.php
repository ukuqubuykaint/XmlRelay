<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

use function count;
use function explode;
use function is_array;
use function is_object;
use function is_string;
use function json_decode;
use function libxml_clear_errors;
use function libxml_disable_entity_loader;
use function libxml_use_internal_errors;
use function parse_str;
use function simplexml_load_string;
use function strtolower;
use function trim;

use const LIBXML_VERSION;

/** @api */
class BodyParsingMiddleware implements MiddlewareInterface
{
    /**
     * @var callable[]
     */
    protected array $bodyParsers;

    /**
     * @param callable[] $bodyParsers list of body parsers as an associative array of mediaType => callable
     */
    public function __construct(array $bodyParsers = [])
    {
        $this->registerDefaultBodyParsers();

        foreach ($bodyParsers as $mediaType => $parser) {
            $this->registerBodyParser($mediaType, $parser);
        }
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();

        if (empty($parsedBody)) {
            $parsedBody = $this->parseBody($request);
            $request = $request->withParsedBody($parsedBody);
        }

        return $handler->handle($request);
    }

    /**
     * @param string $mediaType A HTTP media type (excluding content-type params).
     * @param callable $callable A callable that returns parsed contents for media type.
     */
    public function registerBodyParser(string $mediaType, callable $callable): self
    {
        $this->bodyParsers[$mediaType] = $callable;
        return $this;
    }

    /**
     * @param string $mediaType A HTTP media type (excluding content-type params).
     */
    public function hasBodyParser(string $mediaType): bool
    {
        return isset($this->bodyParsers[$mediaType]);
    }

    /**
     * @param string $mediaType A HTTP media type (excluding content-type params).
     * @throws RuntimeException
     */
    public function getBodyParser(string $mediaType): callable
    {
        if (!isset($this->bodyParsers[$mediaType])) {
            throw new RuntimeException('No parser for type ' . $mediaType);
        }
        return $this->bodyParsers[$mediaType];
    }

    protected function registerDefaultBodyParsers(): void
    {
        $this->registerBodyParser('application/json', static function ($input) {
            /** @var string $input */
            $result = json_decode($input, true);

