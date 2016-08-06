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
use GeneratedHydrator\Configuration;
use Zend\Hydrator\HydratorInterface;

/**
 * Class HydratorFactory
 *
 * @package    Somnambulist\EntityValidation\Factories
 * @subpackage Somnambulist\EntityValidation\Factories\HydratorFactory
 * @author     Dave Redfern
 */
class HydratorFactory
{

    /**
     * @var array
     */
    private $instances = [];



    /**
     * @param object $entity
     *
     * @return HydratorInterface
     */
    public function create($entity)
    {
        $class = ClassUtils::getClass($entity);

        if (!isset($this->instances[$class])) {
            $config        = new Configuration($class);
            $hydratorClass = $config->createFactory()->getHydratorClass();

            $this->instances[$class] = new $hydratorClass();
        }

        return $this->instances[$class];
    }

    /**
     * Unsets and removes all cached Hydrator instances
     */
    public function clear()
    {
        foreach (array_keys($this->instances) as $key) {
            $this->instances[$key] = null;
            unset($this->instances[$key]);
        }

        $this->instances = [];
    }
}
