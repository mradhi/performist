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
use Performist\Mock\InvalidAction;
use Performist\Mock\SomeAction;
use Performist\Mock\SomeHandler;
use Performist\Mock\UndefinedAction;
use Performist\Mock\ValidAction;
use PHPUnit\Framework\TestCase;

class RegistryTest extends TestCase
{
    public function testRegisterAction(): void
    {
        $registry = new Registry();

        $registry->add(ValidAction::class);

        $this->assertNull($registry->get(ValidAction::class));

        $registry->add(SomeAction::class, $handler = new SomeHandler());

        $this->assertSame($handler, $registry->get(SomeAction::class));

        // Add the same action one more time
        try {
            $registry->add(ValidAction::class);
            $this->fail('No exception thrown.');
        } catch (PerformistException $e) {
            $this->assertEquals('"Performist\\Mock\\ValidAction" is already registered inside the container.', $e->getMessage());
        }

        // Add invalid action class.
        try {
            $registry->add(InvalidAction::class);
            $this->fail('No exception thrown.');
        } catch (PerformistException $e) {
            $this->assertSame('"Performist\\Mock\\InvalidAction" should implements "Performist\\ActionInterface".', $e->getMessage());
        }

        // Get undefined action handler
        try {
            $registry->get(UndefinedAction::class);
            $this->fail('No exception thrown.');
        } catch (PerformistException $e) {
            $this->assertSame('The action class "Performist\\Mock\\UndefinedAction" is not registered inside the container.', $e->getMessage());
        }
    }
}
