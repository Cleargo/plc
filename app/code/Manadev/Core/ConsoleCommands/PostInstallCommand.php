<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\ConsoleCommands;

use Manadev\Core\Contracts\PostInstallScript;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PostInstallCommand extends Command
{

    /**
     * @var \Manadev\Core\Registries\PostInstallScripts
     */
    protected $postInstallScriptRegistry;

    public function __construct(
        \Manadev\Core\Registries\PostInstallScripts $postInstallScriptRegistry,
        $name = null
    ) {
        $this->postInstallScriptRegistry = $postInstallScriptRegistry;
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('mana:post-install')
            ->setDescription("Executes post installation code for all MANAdev Extensions.");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        /** @var PostInstallScript $postInstallScript */
        foreach($this->postInstallScriptRegistry->getList() as $postInstallScript) {
            $postInstallScript->execute();
        }
        $output->writeln("Command `mana:post-install` successfully completed!");
    }
}