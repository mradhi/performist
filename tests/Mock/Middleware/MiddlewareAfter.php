<?php
/*
 * This file is part of the Performist package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\Performist\Tests\Mock\Middleware;


use Closure;
use Guennichi\Performist\ActionInterface;
use Guennichi\Performist\MiddlewareInterface;
use Guennichi\Performist\Tests\Mock\SomeAction;

class MiddlewareAfter implements MiddlewareInterface
{
    /**
     * @inheritDoc
     *
     * @param SomeAction $action
     */
    public function handle(ActionInterface $action, Closure $next)
    {
        $result = $next($action);

        $action->runs[] = 'after';

        return $result;
    }
}
