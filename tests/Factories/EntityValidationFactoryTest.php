<?php

namespace Somnambulist\Tests\EntityValidation\Factories;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Factory;
use Somnambulist\EntityValidation\Contracts\EntityRules;
use Somnambulist\EntityValidation\Exceptions\ValidationConfigurationException;
use Somnambulist\EntityValidation\Factories\EntityValidationFactory;
use Somnambulist\EntityValidation\Factories\HydratorFactory;
use Symfony\Component\Translation\Translator;

/**
 * Class EntityValidationFactoryTest
 *
 * @package    Factories
 * @subpackage Factories\EntityValidationFactoryTest
 * @author     Dave Redfern
 */
class EntityValidationFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @group factories
     * @group entity-validation
     */
    public function testValidate()
    {
        $factory = new EntityValidationFactory(
            new HydratorFactory(),
            new Factory(new Translator('en')),
            [
                \MyEntity::class => new \MyEntityEntityRules,
            ]
        );

        $this->assertFalse($factory->validate(new \MyEntity(null)));
        $this->assertTrue($factory->validate(new \MyEntity('test', Carbon::now())));
    }

    /**
     * @group factories
     * @group entity-validation
     */
    public function testValidateRaisesExceptionIfNoMapping()
    {
        $factory = new EntityValidationFactory(
            new HydratorFactory(),
            new Factory(new Translator('en')), []
        );

        $this->expectException(ValidationConfigurationException::class);
        $factory->validate(new \MyEntity(null));
    }

    /**
     * @group factories
     * @group entity-validation
     */
    public function testGetEntityRules()
    {
        $factory = new EntityValidationFactory(
            new HydratorFactory(),
            new Factory(new Translator('en')),
            [
                \MyEntity::class => new \MyEntityEntityRules,
            ]
        );

        $this->assertInstanceOf(EntityRules::class, $factory->getEntityRulesFor(new \MyEntity(null)));
    }

    /**
     * @group factories
     * @group entity-validation
     */
    public function testGetEntityRulesRaisesExceptionIfNotConfigured()
    {
        $factory = new EntityValidationFactory(
            new HydratorFactory(),
            new Factory(new Translator('en')), []
        );

        $this->expectException(ValidationConfigurationException::class);
        $factory->getEntityRulesFor(new \MyEntity(null));
    }

    /**
     * @group factories
     * @group entity-validation
     */
    public function testGetValidatorFor()
    {
        $factory = new EntityValidationFactory(
            new HydratorFactory(),
            new Factory(new Translator('en')),
            [
                \MyEntity::class => new \MyEntityEntityRules,
            ]
        );

        $this->assertInstanceOf(Validator::class, $factory->getValidatorFor(new \MyEntity(null)));
    }

    /**
     * @group factories
     * @group entity-validation
     */
    public function testCanAddMappingRules()
    {
        $factory = new EntityValidationFactory(
            new HydratorFactory(),
            new Factory(new Translator('en')), []
        );

        $factory->addEntityRulesMapping(\MyEntity::class, new \MyEntityEntityRules());

        $this->assertCount(1, $factory->getEntityRulesMappings());
    }

    /**
     * @group factories
     * @group entity-validation
     */
    public function testCanRemoveMappingRules()
    {
        $factory = new EntityValidationFactory(
            new HydratorFactory(),
            new Factory(new Translator('en')), []
        );

        $factory->addEntityRulesMapping(\MyEntity::class, new \MyEntityEntityRules());

        $this->assertCount(1, $factory->getEntityRulesMappings());

        $factory->removeEntityRulesMapping(\MyEntity::class);

        $this->assertCount(0, $factory->getEntityRulesMappings());
    }
}
