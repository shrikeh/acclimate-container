<?php

namespace Jeremeamia\Acclimate\Adapter;

use Jeremeamia\Acclimate\Acclimate;
use Jeremeamia\Acclimate\ContainerInterface;

abstract class AbstractContainerAdapter implements ContainerInterface
{
    /**
     * @var mixed
     */
    protected $container;

    /**
     * @param string     $name
     * @param \Exception $e
     *
     * @return mixed
     * @throws \OutOfBoundsException
     */
    protected function handleMissingItem($name, \Exception $e = null)
    {
        if ($this->missingItemHandler === Acclimate::RETURN_NULL) {
            return null;
        } elseif ($this->missingItemHandler === Acclimate::THROW_EXCEPTION) {
            throw new \OutOfBoundsException("There was no item in the container for name \"{$name}\".", 0, $e);
        } else {
            return call_user_func($this->missingItemHandler, $name);
        }
    }

    /**
     * @var string|callable
     */
    protected $missingItemHandler;

    /**
     * @param mixed           $container
     * @param string|callback $missingItemHandler
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($container, $missingItemHandler = Acclimate::RETURN_NULL)
    {
        $expectedContainerFqcn = $this->getExpectedContainerFqcn();
        if ($container instanceof $expectedContainerFqcn) {
            $this->container = $container;
        } else {
            throw new \InvalidArgumentException("The container must be an instance of {$expectedContainerFqcn}.");
        }

        if ($missingItemHandler === Acclimate::RETURN_NULL
            || $missingItemHandler === Acclimate::THROW_EXCEPTION
            || is_callable($missingItemHandler)
        ) {
            $this->missingItemHandler = $missingItemHandler;
        } else {
            throw new \InvalidArgumentException('The handler must be "RETURN_NULL", "THROW_EXCEPTION", or a callback.');
        }
    }

    /**
     * @return string
     */
    abstract protected function getExpectedContainerFqcn();
}