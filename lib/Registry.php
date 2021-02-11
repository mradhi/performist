<?php
/*
 * This file is part of the Performist package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\Performist;


use Guennichi\Performist\Exception\PerformistException;

class Registry
{
    public array $container = [];

    /**
     * @param string $actionClass
     * @param HandlerInterface|null $handler
     *
     * @throws PerformistException
     */
    public function add(string $actionClass, ?HandlerInterface $handler = null): void
    {
        $this->validateActionClass($actionClass);

        if (array_key_exists($actionClass, $this->container)) {
            if (null !== $existedHandler = $this->container[$actionClass]) {
                throw new PerformistException(sprintf('The action class "%s" already have a handler "%s", you cannot add the new handler "%s" for this action.', $actionClass, get_class($existedHandler), get_class($handler)));
            }

            throw new PerformistException(sprintf('You cannot register the action class "%s" twice.', $actionClass));
        }

        $this->container[$actionClass] = $handler;
    }

    /**
     * @param string $actionClass
     *
     * @return HandlerInterface|null
     *
     * @throws PerformistException
     */
    public function get(string $actionClass): ?HandlerInterface
    {
        $this->validateActionClass($actionClass);

        if (!array_key_exists($actionClass, $this->container)) {
            throw new PerformistException(sprintf('The action class "%s" is not registered inside the container.', $actionClass));
        }

        return $this->container[$actionClass];
    }

    /**
     * @param string $actionClass
     *
     * @throws PerformistException
     */
    private function validateActionClass(string $actionClass): void
    {
        if (!class_exists($actionClass)) {
            throw new PerformistException(sprintf('"%s" class does not exist.', $actionClass));
        }
    }
}
