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

namespace Somnambulist\EntityValidation\Commands;

use GeneratedHydrator\Configuration;
use Illuminate\Config\Repository;
use Illuminate\Console\Command;

/**
 * Class GenerateHydrators
 *
 * @package    Somnambulist\EntityValidation\Commands
 * @subpackage Somnambulist\EntityValidation\Commands\GenerateHydrators
 * @author     Dave Redfern
 */
class GenerateHydrators extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doctrine:generate:hydrators';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates hydrators for the configured entities.';

    /**
     * @var Repository
     */
    protected $config;



    /**
     * Constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        parent::__construct();

        $this->config = $config;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = storage_path($this->config->get('doctrine_hydrators.cache_path', 'cache/hydrators'));
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        array_map('unlink', glob("$path/*") ?: []);

        foreach ($this->config->get('doctrine_hydrators.entities', []) as $class) {
            $this->output->writeln("Processing hydrator for <info>{$class}</info>");

            $config = new Configuration($class);
            $config->setGeneratedClassesTargetDir($path);
            $config->setAutoGenerateProxies(true);
            $config->createFactory()->getHydratorClass();
        }

        $this->output->writeln("Hydrator classes generated to <info>$path</info>");
    }
}
