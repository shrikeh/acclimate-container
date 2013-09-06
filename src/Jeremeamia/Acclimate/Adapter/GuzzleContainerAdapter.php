<?php

namespace Jeremeamia\Acclimate\Adapter;

use Guzzle\Service\Builder\ServiceBuilderInterface;
use Guzzle\Service\Exception\ServiceNotFoundException;
use Jeremeamia\Acclimate\ContainerInterface as AcclimateContainerInterface;
use Jeremeamia\Acclimate\ServiceNotFoundException as AcclimateException;

class GuzzleContainerAdapter implements AcclimateContainerInterface
{
    /**
     * @var ServiceBuilderInterface
     */
    private $container;

    /**
     * @param ServiceBuilderInterface $container
     */
    public function __construct(ServiceBuilderInterface $container)
    {
        $this->container = $container;
    }

    public function get($name)
    {
        try {
            return $this->container->get($name);
        } catch (ServiceNotFoundException $prev) {
            throw AcclimateException::fromName($name, $prev);
        }
    }

    public function has($name)
    {
        return isset($this->container[$name]);
    }
}