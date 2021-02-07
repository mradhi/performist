<?php
/*
 * This file is part of the Performist package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Performist\Mock;


use Performist\HandlerInterface;

class InvalidHandler implements HandlerInterface
{
    // Does not contains an __invoke() method.
}
