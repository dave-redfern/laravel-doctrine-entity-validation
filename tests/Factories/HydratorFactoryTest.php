<?php

namespace Somnambulist\Tests\EntityValidation\Factories;

use Somnambulist\EntityValidation\Factories\HydratorFactory;
use Zend\Hydrator\HydrationInterface;

/**
 * Class HydratorFactoryTest
 *
 * @package    Factories
 * @subpackage Factories\HydratorFactoryTest
 * @author     Dave Redfern
 */
class HydratorFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @group factories
     * @group hydrator-factory
     */
    public function testCreate()
    {
        $factory = new HydratorFactory();

        $hydrator = $factory->create(new \MyEntity());

        $this->assertInstanceOf(HydrationInterface::class, $hydrator);
    }

    /**
     * @group factories
     * @group hydrator-factory
     */
    public function testCreateCachesCreatedInstances()
    {
        $factory = new HydratorFactory();

        $hydrator1 = $factory->create(new \MyEntity());
        $this->assertInstanceOf(HydrationInterface::class, $hydrator1);

        $hydrator2 = $factory->create(new \MyEntity());

        $this->assertSame($hydrator1, $hydrator2);
    }

    /**
     * @group factories
     * @group hydrator-factory
     */
    public function testClearRemovesInstances()
    {
        $factory = new HydratorFactory();

        $hydrator1 = $factory->create(new \MyEntity());
        $this->assertInstanceOf(HydrationInterface::class, $hydrator1);
        $hydrator2 = $factory->create(new \MyEntity());

        $this->assertSame($hydrator1, $hydrator2);

        $factory->clear();

        $hydrator3 = $factory->create(new \MyEntity());

        $this->assertNotSame($hydrator1, $hydrator3);
    }
}
