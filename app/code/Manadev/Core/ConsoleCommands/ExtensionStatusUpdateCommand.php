<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core\ConsoleCommands;

use Manadev\Core\Model\Extension;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ExtensionStatusUpdateCommand extends Command
{
    /**
     * @var \Manadev\Core\Model\Feature\Config
     */
    private $extensionConfig;
    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    /**
     * Constructor.
     *
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     *
     * @throws \LogicException When the command name is empty
     *
     * @api
     */
    public function __construct(
        \Manadev\Core\Model\Feature\Config $extensionConfig,
        \Magento\Framework\Filesystem $filesystem,
        $name = null
    ) {
        $this->extensionConfig = $extensionConfig;
        $this->filesystem = $filesystem;
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('mana:update')
            ->setDescription("Renames MANAdev modules' `registration.php` to `registration.php_` if it is disabled and sets it back to `registration.php` if it is enabled.");
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        foreach($this->extensionConfig->getExtensions(0) as $extension) {
            $extensionChanged = $extension->updateModuleXml();

            if(!is_null($extensionChanged) && $extensionChanged) {
                $this->_notifyChange($extension, $output);
            }
            /** @var Extension $feature */
            foreach($extension->getData('features') as $feature) {
                $featureChanged = $feature->updateModuleXml();
                if (!is_null($featureChanged) && $featureChanged) {
                    $this->_notifyChange($feature, $output);
                }
            }

            $this->_cascadeToRemovedExtensions($extension, $output);
            $changes = $this->extensionConfig->disableDependentModules();
            foreach($changes as $changedtmpExtension) {
                $this->_notifyChange($changedtmpExtension, $output);
            }
        }

        $this->executeMagentoCommand("setup:upgrade", $output);
        $this->executeMagentoCommand("cache:clean", $output);
        $this->executeMagentoCommand("mana:post-install", $output);
    }

    protected function _cascadeToRemovedExtensions($extension, $output) {
        foreach($this->extensionConfig->getExtensionsRemovedByModule($extension->getData('module')) as $removedExtension) {
            $changed = $removedExtension->setData('is_enabled', $extension->getData('is_enabled'))->save()->updateModuleXml();
            if(!is_null($changed) && $changed) {
                $this->_notifyChange($removedExtension, $output);
            }
            $this->_cascadeToRemovedExtensions($removedExtension, $output);
        }
    }

    protected function _notifyChange(Extension $extension, OutputInterface $output) {
        $status = $extension->getData('is_enabled') ? "enabled" : "disabled";
        $output->writeln("Module `".$extension->getData('module')."` has been ". $status .".");
    }

    private function executeMagentoCommand($command, OutputInterface $output) {
        $rootPath = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::ROOT)->getAbsolutePath();
        $output->writeln("Running {$command} command...");
        @exec("php {$rootPath}bin/magento {$command}");
    }
}