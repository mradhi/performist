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


use Closure;

class HandlerPeeler
{
    /**
     * @var MiddlewareInterface[]
     */
    private array $middlewares = [];

    /**
     * @param MiddlewareInterface[] $middlewares
     *
     * @return HandlerPeeler
     */
    public function middlewares(array $middlewares): self
    {
        $this->middlewares = $middlewares;

        return $this;
    }

    public function peel($action, Closure $next)
    {
        $handleFunction = $this->createHandleFunction($next);

        $layers = array_reverse($this->middlewares);

        $complete = array_reduce($layers, function ($nextLayer, $layer) {
            return $this->createLayer($nextLayer, $layer);
        }, $handleFunction);

        return $complete($action);
    }

    private function createLayer(Closure $nextLayer, MiddlewareInterface $layer): Closure
    {
        return function ($action) use ($nextLayer, $layer) {
            return $layer->handle($action, $nextLayer);
        };
    }

    private function createHandleFunction(Closure $handle): Closure
    {
        return function ($action) use ($handle) {
            return $handle($action);
        };
    }
}
