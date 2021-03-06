<?php
/*
 * This file is part of the Performist package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\Performist\Tests\Mock;


use Guennichi\Performist\HandlerInterface;

class SomeHandler implements HandlerInterface
{
    public function __invoke(SomeAction $action): SomeAction
    {
        $action->runs[] = 'handled';

        return $action;
    }
}
