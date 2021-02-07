<?php
/*
 * This file is part of the Performist package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Performist;


use Performist\Exception\PerformistException;
use Performist\Mock\InvalidHandler;
use Performist\Mock\Middleware\MiddlewareAfter;
use Performist\Mock\Middleware\MiddlewareBefore;
use Performist\Mock\SomeAction;
use Performist\Mock\SomeHandler;
use Performist\Mock\ValidAction;
use PHPUnit\Framework\TestCase;

class PerformerTest extends TestCase
{
    public function testPerformAction(): void
    {
        $registry = new Registry();
        $registry->add(SomeAction::class, new SomeHandler());

        $performer = new Performer($registry, new HandlerPeeler());
        $result = $performer->perform(new SomeAction(), [new MiddlewareBefore(), new MiddlewareAfter()]);

        $this->assertInstanceOf(SomeAction::class, $result);

        $expectedRuns = ['before', 'handled', 'after'];
        $this->assertSame($expectedRuns, $result->runs);

        // Register invalid handler for ValidAction class.
        $registry->add(ValidAction::class, new InvalidHandler());
        try {
            $performer->perform(new ValidAction());
            $this->fail('No exception thrown.');
        } catch (PerformistException $e) {
            $this->assertSame('"Performist\\Mock\\InvalidHandler" handler should implements the invoke() method.', $e->getMessage());
        }
    }
}
