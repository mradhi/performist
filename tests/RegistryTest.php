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
use Guennichi\Performist\Registry;
use Guennichi\Performist\Tests\Mock\SomeAction;
use Guennichi\Performist\Tests\Mock\SomeHandler;
use Guennichi\Performist\Tests\Mock\UndefinedAction;
use Guennichi\Performist\Tests\Mock\ValidAction;
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
            $this->assertEquals('"Guennichi\\Performist\\Tests\\Mock\\ValidAction" is already registered inside the container.', $e->getMessage());
        }

        // Add invalid action class.
        try {
            $registry->add('Guennichi\\Performist\\Tests\\Mock\\InvalidAction');
            $this->fail('No exception thrown.');
        } catch (PerformistException $e) {
            $this->assertSame('"Guennichi\\Performist\\Tests\\Mock\\InvalidAction" class does not exist.', $e->getMessage());
        }

        // Get undefined action handler
        try {
            $registry->get(UndefinedAction::class);
            $this->fail('No exception thrown.');
        } catch (PerformistException $e) {
            $this->assertSame('The action class "Guennichi\\Performist\\Tests\\Mock\\UndefinedAction" is not registered inside the container.', $e->getMessage());
        }
    }
}
