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

interface MiddlewareInterface
{
    /**
     * @param ActionInterface $action
     * @param Closure         $next
     *
     * @return mixed
     */
    public function handle(ActionInterface $action, Closure $next);
}
