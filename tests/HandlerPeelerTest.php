<?php
/*
 * This file is part of the Performist package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\Performist\Tests;


use Guennichi\Performist\HandlerPeeler;
use Guennichi\Performist\Tests\Mock\Middleware\MiddlewareAfter;
use Guennichi\Performist\Tests\Mock\Middleware\MiddlewareBefore;
use Guennichi\Performist\Tests\Mock\Middleware\MiddlewareBetween;
use Guennichi\Performist\Tests\Mock\SomeAction;
use PHPUnit\Framework\TestCase;

class HandlerPeelerTest extends TestCase
{
    public function testMiddlewareLayers(): void
    {
        $baseMiddleware = new HandlerPeeler();
        $end = $baseMiddleware
            ->middlewares([
                new MiddlewareBetween(),
                new MiddlewareBefore(),
                new MiddlewareAfter()
            ])
            ->peel(new SomeAction(), function (SomeAction $action) {
                $action->runs[] = 'core';

                return $action;
            });

        $expectedRuns = ['between_before', 'before', 'core', 'after', 'between_after'];

        $this->assertSame($expectedRuns, $end->runs);
    }
}
