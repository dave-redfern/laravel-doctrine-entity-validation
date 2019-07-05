This project is no longer being maintained.

## Entity Validation for Laravel-Doctrine ORM

Provides integration between standard Doctrine entities and the Laravel Validator
by using Hydrators.

### Requirements

 * PHP 7+
 * Laravel 5.2+
 * laravel-doctrine/orm

### Installation

Install using composer, or checkout / pull the files from github.com.

 * composer install somnambulist/laravel-doctrine-entity-validation
 * add the service provider to your config/app.php file
 * php artisan vendor:publish

Two new config files will added:

 * doctrine_hydrators.php
 * doctrine_validation.php

Add the entity class names to the hydrators config file to have hydrators made.
Add a mapping between the entity and a rules class in validation to allow the factory
class to create Validator instances.

The validation rules should implement the EntityRules contract or extend:
`Somnambulist\EntityValidation\AbstractEntityRules` class. The rules class should contain
the basic rules needed to validate the entity. This is **NOT** form validation! These
rules are the basic requirements for your domain entities to be valid.

The validation rules can then be added to your form requests or validation rules.
E.g.: a User entity may have EntityRules requiring a name, email and username but in
the AddUserFormRequest, Roles and Permissions may be additionally required. The
entity rules would look something like:

    class UserEntityRules extends AbstractRules
    {
        public function supports($entity)
        {
            return $entity instanceof User;
        }
        
        protected function buildRules($entity)
        {
            return [
                'name' => 'required|min:1',
                'email' => 'required|email|unique:User,email,' . ($entity->getId() ?: 'null'),
                'username' => 'required|alphanum|unique:User,username,' . ($entity->getId() ?: 'null'),
            ];
        }
    }

As the entity is passed in, you can access any method and create complex rules.

The entity validation factory can then be type-hinted or fetched from the container:

    class SomeClass ...
    {
        public function __construct(EntityValidationFactory $validationFactory)
        {
            $this->factory = $validationFactory;
        }
        public function someMethod()
        {
            if ($this->factory->validate($user)) {
            
            }
        }
    }

### Generating Hydrators

An extra command is provided to make generating the hydrators easier:

    php artisan doctrine:generate:hydrators

These will be cached to the file system in the storage/cache/hydrators folder by
default. Configure the storage folder in the hydrators config file.

This command can be added to `composer install|update` so that the hydrators are
created automatically as changes are made or code deployed.

 * _Note_: it is not a requirement to cache the hydrators, however it offers much
   better performance in production.
 * _Note_: it is good to add doctrine:generate:proxies before the hydrators.

## Links

 * [Entity Auditing (port of SimpleThings: EntityAudit)](https://github.com/dave-redfern/laravel-doctrine-entity-audit)
 * [Domain Events for Laravel with Doctrine](https://github.com/dave-redfern/laravel-doctrine-domain-events)
 * [Multi-Tenancy for Laravel with Doctrine](https://github.com/dave-redfern/laravel-doctrine-tenancy)
 * [Laravel Doctrine](http://laraveldoctrine.org)
 * [Laravel](http://laravel.com)
 * [Doctrine](http://doctrine-project.org)
