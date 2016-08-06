<?php

namespace Somnambulist\Tests\EntityValidation\Commands;

use TestContainer;
use Illuminate\Config\Repository;
use Somnambulist\EntityValidation\Commands\GenerateHydrators;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;


/**
 * Class MakeEntityTest
 *
 * @author Dave Redfern
 */
class GenerateHydratorsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var string
     */
    protected $root;

    /**
     * @var GenerateHydrators
     */
    protected $command;

    protected function setUp()
    {
        // Hydrator uses realpath() that does not work with vfsStream :(
        $this->root = realpath(__DIR__ . '/../_output/');

        $config = [
            'doctrine_hydrators' => [
                'cache_path' => 'cache/hydrators',
                'entities' => [
                    \MyEntity::class,
                ]
            ],
        ];

        $container = new TestContainer();
        $container->instance('path', $this->root);

        $this->command = new GenerateHydrators(new Repository($config));
        $this->command->setLaravel($container);
    }

    protected function tearDown()
    {
        $this->command = null;

        array_map('unlink', glob($this->root . '/cache/hydrators/*.*'));
        rmdir($this->root . '/cache/hydrators');
        rmdir($this->root . '/cache');
    }


    /**
     * @group commands
     * @group generate-hydrators
     */
    public function testCanGenerateHydratorClassesToCacheFolder()
    {
        $input = new ArrayInput([]);
        $output = new BufferedOutput();

        $this->command->run($input, $output);

        $output = $output->fetch();

        $this->assertTrue(is_dir($this->root . '/cache'));
        $this->assertTrue(is_dir($this->root . '/cache/hydrators'));
        $this->assertContains('MyEntity', $output);
        $this->assertContains("Hydrator classes generated to {$this->root}/cache/hydrators", $output);
    }
}
