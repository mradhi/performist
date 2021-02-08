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

interface PerformerInterface
{
    /**
     * Executes the handler of a given action.
     *
     * @param mixed $action
     * @param MiddlewareInterface[] $middlewares
     *
     * @return mixed
     *
     * @throws PerformistException
     */
    public function perform($action, array $middlewares = []);
}
