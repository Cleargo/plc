<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core\ConsoleCommands;

use Manadev\Core\Registries\ConfigurationReaders;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Dumper;

class SaveConfigCommand extends Command
{
    /**
     * @var ConfigurationReaders
     */
    protected $readers;
    /**
     * @var array
     */
    protected $scopes;

    public function __construct(ConfigurationReaders $readers, array $scopes, $name = null) {
        parent::__construct($name);
        $this->readers = $readers;
        $this->scopes = array_keys($scopes);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('mana:save-config')
            ->setDescription('Saves merged configuration in /var directory')
            ->setDefinition([
                new InputArgument('config-name', InputArgument::OPTIONAL, "Configuration name ('di' for 'di.xml')"),
            ]);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        if ($input->hasArgument('configName')) {
            $configName = $input->getArgument('configName');
            if (!($reader = $this->readers->get($configName))) {
                throw new \InvalidArgumentException(sprintf(
                    "Configuration name '%s' is not defined in %s registry.",
                    $configName, ConfigurationReaders::class));
            }
            $readers = [$reader];
        }
        else {
            $readers = $this->readers->getList();
        }

        foreach ($readers as $configName => $reader) {
            $output->write("Dumping $configName.xml ...");
            $this->save($configName, $reader->read());
            if ($configName != 'config') {
                foreach ($this->scopes as $scope) {
                    $this->save($configName . '-' . $scope, $reader->read($scope));
                }
            }
            $output->writeln("OK");
        }

    }

    protected function save($name, $data) {
        if (!is_array($data) || !count($data)) {
            return;
        }

        $filename = BP . "/var/merged-config/$name.yml";
        if (!is_dir(dirname($filename))) {
            mkdir(dirname($filename), 0777, true);
        }

        $dumper = new Dumper();
        file_put_contents($filename, $dumper->dump($data, 100));
    }
}