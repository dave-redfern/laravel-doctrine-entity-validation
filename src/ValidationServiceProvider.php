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

namespace Somnambulist\EntityValidation;

use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;
use Somnambulist\EntityValidation\Factories;

/**
 * Class ValidationServiceProvider
 *
 * @package    Somnambulist\Doctrine
 * @subpackage Somnambulist\EntityValidation\ValidationServiceProvider
 * @author     Dave Redfern
 */
class ValidationServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes($this->getConfigPaths(), 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();

        $config = $this->app->make('config');

        $this->registerEntityValidator($config);
        $this->registerCommands($config);
    }



    /**
     * Merge config
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom($this->getHydratorsConfigPath(),  'doctrine_hydrators');
        $this->mergeConfigFrom($this->getValidationConfigPath(), 'doctrine_validation');
    }

    /**
     * Registers the entity validation mappings
     *
     * @param Repository $config
     *
     * @return void
     */
    protected function registerEntityValidator(Repository $config)
    {
        $this->app->singleton(Factories\EntityValidationFactory::class, function ($app) use ($config) {
            $factory = new Factories\EntityValidationFactory(
                $app->make(Factories\HydratorFactory::class), $app['validator']
            );

            foreach ($config->get('doctrine_validation.mappings') as $entity => $rules) {
                $factory->addEntityRulesMapping($entity, $app->make($rules));
            }

            return $factory;
        });
    }

    /**
     * Register the CLI commands with console
     *
     * @param Repository $config
     */
    protected function registerCommands(Repository $config)
    {
        $this->commands([
            Commands\GenerateHydrators::class,
        ]);
    }

    /**
     * @return string
     */
    protected function getConfigPaths()
    {
        return [
            $this->getHydratorsConfigPath()  => config_path('doctrine_hydrators.php'),
            $this->getValidationConfigPath() => config_path('doctrine_validation.php'),
        ];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getConfigPathFor($name)
    {
        return __DIR__ . sprintf('/../config/doctrine_%s.php', $name);
    }

    /**
     * @return string
     */
    protected function getHydratorsConfigPath()
    {
        return $this->getConfigPathFor('hydrators');
    }

    /**
     * @return string
     */
    protected function getValidationConfigPath()
    {
        return $this->getConfigPathFor('validation');
    }

    /**
     * @return array
     */
    public static function compiles()
    {
        return [
            __DIR__ . '/Contracts/EntityRules.php',
            __DIR__ . '/Validation/AbstractRules.php',
            __DIR__ . '/Factories/HydratorFactory.php',
            __DIR__ . '/Factories/EntityValidationFactory.php',
        ];
    }
}
