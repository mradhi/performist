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


use Guennichi\Performist\Exception\PerformistException;
use Guennichi\Performist\HandlerPeeler;
use Guennichi\Performist\Performer;
use Guennichi\Performist\Registry;
use Guennichi\Performist\Tests\Mock\InvalidHandler;
use Guennichi\Performist\Tests\Mock\Middleware\MiddlewareAfter;
use Guennichi\Performist\Tests\Mock\Middleware\MiddlewareBefore;
use Guennichi\Performist\Tests\Mock\SomeAction;
use Guennichi\Performist\Tests\Mock\SomeHandler;
use Guennichi\Performist\Tests\Mock\ValidAction;
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
            $this->assertSame('"Guennichi\\Performist\\Tests\\Mock\\InvalidHandler" handler should implements the invoke() method.', $e->getMessage());
        }

        // Add action without handler
        $registry->add(ActionWithoutHandler::class);
        $result = $performer->perform(new ActionWithoutHandler());

        $this->assertNull($result);
    }
}

class ActionWithoutHandler
{

}
