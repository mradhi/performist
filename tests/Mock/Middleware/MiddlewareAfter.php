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
use Guennichi\Performist\MiddlewareInterface;

class MiddlewareAfter implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function handle($action, Closure $next)
    {
        $result = $next($action);

        $action->runs[] = 'after';

        return $result;
    }
}
