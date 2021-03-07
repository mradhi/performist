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

class Performer
{
    protected Registry $registry;

    protected HandlerPeeler $handlerPeeler;

    public function __construct(Registry $registry, HandlerPeeler $handlerPeeler)
    {
        $this->registry = $registry;
        $this->handlerPeeler = $handlerPeeler;
    }

    /**
     * Executes the handler of a given action.
     *
     * @param mixed $action
     * @param HandlerInterface|null $handler
     * @param MiddlewareInterface[] $middlewares
     * @param mixed $context
     *
     * @return mixed
     *
     * @throws PerformistException
     */
    public function perform($action, ?HandlerInterface $handler = null, array $middlewares = [], $context = null)
    {
        if (null === $handler) {
            $handler = $this->registry->get(get_class($action));
        }

        if (null === $handler) {
            return null;
        }

        if (!is_callable($handler)) {
            throw new PerformistException(sprintf('"%s" handler should implements the invoke() method.', get_class($handler)));
        }

        return $this->handlerPeeler
            ->middlewares($middlewares)
            ->peel($action, function ($action) use ($handler, $context) {
                return $handler($action, $context);
            });
    }
}
