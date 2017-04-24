<?php

declare(strict_types=1);

namespace Slim\Routing;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector as FastRouteCollector;
use FastRoute\RouteParser\Std;
use Slim\Interfaces\DispatcherInterface;
use Slim\Interfaces\RouteCollectorInterface;

class Dispatcher implements DispatcherInterface
{
    private RouteCollectorInterface $routeCollector;

    private ?FastRouteDispatcher $dispatcher = null;

    public function __construct(RouteCollectorInterface $routeCollector)
    {
        $this->routeCollector = $routeCollector;
    }

    protected function createDispatcher(): FastRouteDispatcher
    {
        if ($this->dispatcher) {
            return $this->dispatcher;
        }

        $routeDefinitionCallback = function (FastRouteCollector $r): void {
