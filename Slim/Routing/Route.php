<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Routing;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Handlers\Strategies\RequestHandler;
use Slim\Handlers\Strategies\RequestResponse;
use Slim\Interfaces\AdvancedCallableResolverInterface;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\InvocationStrategyInterface;
use Slim\Interfaces\RequestHandlerInvocationStrategyInterface;
use Slim\Interfaces\RouteGroupInterface;
use Slim\Interfaces\RouteInterface;
use Slim\MiddlewareDispatcher;

use function array_key_exists;
use function array_replace;
use function array_reverse;
use function class_implements;
use function in_array;
use function is_array;

/**
 * @api
 * @template TContainerInterface of (ContainerInterface|null)
 */
class Route implements RouteInterface, RequestHandlerInterface
{
    /**
     * HTTP methods supported by this route
     *
     * @var string[]
     */
    protected array $methods = [];

    /**
     * Route identifier
     */
    protected string $identifier;

    /**
     * Route name
     */
    protected ?string $name = null;

    /**
     * Parent route groups
     *
     * @var RouteGroupInterface[]
     */
    protected array $groups;

    protected InvocationStrategyInterface $invocationStrategy;

    /**
     * Route parameters
     *
     * @var array<string, string>
     */
    protected array $arguments = [];

    /**
     * Route arguments parameters
     *
     * @var array<string, string>
     */
    protected array $savedArguments = [];

    /**
     * Container
     * @var TContainerInterface $container
     */
    protected ?ContainerInterface $container = null;

    /** @var MiddlewareDispatcher<TContainerInterface> $middlewareDispatcher */
    protected MiddlewareDispatcher $middlewareDispatcher;

    /**
     * Route callable
     *
     * @var callable|array{class-string, string}|string
     */
    protected $callable;

    protected CallableResolverInterface $callableResolver;

    protected ResponseFactoryInterface $responseFactory;

    /**
     * Route pattern
     */
    protected string $pattern;

    protected bool $groupMiddlewareAppended = false;

    /**
     * @param string[] $methods The route HTTP methods
     * @param string $pattern The route pattern
     * @param callable|array{class-string, string}|string $callable The route callable
     * @param ResponseFactoryInterface $responseFactory
     * @param CallableResolverInterface $callableResolver
     * @param TContainerInterface $container
     * @param InvocationStrategyInterface|null $invocationStrategy
     * @param RouteGroupInterface[] $groups The parent route groups
     * @param int $identifier The route identifier
     */
    public function __construct(
        array $methods,
        string $pattern,
        $callable,
        ResponseFactoryInterface $responseFactory,
        CallableResolverInterface $callableResolver,
        ?ContainerInterface $container = null,
        ?InvocationStrategyInterface $invocationStrategy = null,
        array $groups = [],
        int $identifier = 0
    ) {
        $this->methods = $methods;
        $this->pattern = $pattern;
        $this->callable = $callable;
        $this->responseFactory = $responseFactory;
        $this->callableResolver = $callableResolver;
        $this->container = $container;
        $this->invocationStrategy = $invocationStrategy ?? new RequestResponse();
        $this->groups = $groups;
        $this->identifier = 'route' . $identifier;
        $this->middlewareDispatcher = new MiddlewareDispatcher($this, $callableResolver, $container);
    }

    public function getCallableResolver(): CallableResolverInterface
    {
        return $this->callableResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getInvocationStrategy(): InvocationStrategyInterface
    {
        return $this->invocationStrategy;
    }

    /**
     * {@inheritdoc}
