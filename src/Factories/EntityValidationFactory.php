<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Somnambulist\EntityValidation\Factories;

use Doctrine\Common\Util\ClassUtils;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as ValidatorFactory;
use Somnambulist\EntityValidation\Contracts\EntityRules;
use Somnambulist\EntityValidation\Exceptions\ValidationConfigurationException;

/**
 * Class EntityValidationFactory
 *
 * @package    Somnambulist\EntityValidation\Factories
 * @subpackage Somnambulist\EntityValidation\Factories\EntityValidationFactory
 * @author     Dave Redfern
 */
class EntityValidationFactory
{

    /**
     * @var HydratorFactory
     */
    protected $hydrators;

    /**
     * @var ValidatorFactory
     */
    protected $validator;

    /**
     * @var Collection
     */
    protected $ruleMappings;



    /**
     * Constructor.
     *
     * @param HydratorFactory  $hydrators
     * @param ValidatorFactory $validator
     * @param array            $mappings  [entity_class => entity_rules object]
     */
    public function __construct(HydratorFactory $hydrators, ValidatorFactory $validator, array $mappings = [])
    {
        $this->hydrators    = $hydrators;
        $this->validator    = $validator;
        $this->ruleMappings = new Collection($mappings);
    }

    /**
     * Runs the validation on the entity
     *
     * @param object $entity
     *
     * @return bool
     */
    public function validate($entity)
    {
        return $this->getValidatorFor($entity)->passes();
    }

    /**
     * Creates a validator for the entity, using any configured rules
     *
     * @param object $entity
     *
     * @return \Illuminate\Validation\Validator
     */
    public function getValidatorFor($entity)
    {
        $rules = $this->getEntityRulesFor($entity);

        return $this->validator->make($this->extract($entity), $rules->rules($entity), $rules->messages());
    }

    /**
     * @param object|string $entity
     *
     * @return EntityRules
     * @throws ValidationConfigurationException
     */
    public function getEntityRulesFor($entity)
    {
        $class = is_object($entity) ? ClassUtils::getClass($entity) : $entity;

        if (null === $rules = $this->ruleMappings->get($class)) {
            throw ValidationConfigurationException::noEntityRulesMappingExists($class);
        }

        return $rules;
    }

    /**
     * @return Collection
     */
    public function getEntityRulesMappings()
    {
        return $this->ruleMappings;
    }

    /**
     * @param string      $class
     * @param EntityRules $rules
     *
     * @return $this
     */
    public function addEntityRulesMapping($class, EntityRules $rules)
    {
        $this->ruleMappings->put($class, $rules);

        return $this;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function removeEntityRulesMapping($class)
    {
        $this->ruleMappings->forget($class);

        return $this;
    }



    /**
     * Extracts the properties from the object as an array
     *
     * @param object $entity
     *
     * @return array
     */
    protected function extract($entity)
    {
        return $this->hydrators->create($entity)->extract($entity);
    }
}
