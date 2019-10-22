<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Routing;

use FastRoute\RouteParser\Std;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteParserInterface;

use function array_key_exists;
use function array_reverse;
use function http_build_query;
use function implode;
use function is_string;

class RouteParser implements RouteParserInterface
{
    private RouteCollectorInterface $routeCollector;

    private Std $routeParser;

    public function __construct(RouteCollectorInterface $routeCollector)
    {
        $this->routeCollector = $routeCollector;
        $this->routeParser = new Std();
    }

    /**
     * {@inheritdoc}
     */
    public function relativeUrlFor(string $routeName, array $data = [], array $queryParams = []): string
    {
        $route = $this->routeCollector->getNamedRoute($routeName);
        $pattern = $route->getPattern();

        $segments = [];
        $segmentName = '';

        /*
         * $routes is an associative array of expressions representing a route as multiple segments
         * There is an expression for each optional parameter plus one without the optional parameters
         * The most specific is last, hence why we reverse the array before iterating over it
         */
        $expressions = array_reverse($this->routeParser->parse($pattern));
        foreach ($expressions as $expression) {
            foreach ($expression as $segment) {
                /*
