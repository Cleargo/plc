<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Model\Feature\Config;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Config\FileResolverInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class FileResolver implements FileResolverInterface
{
    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    public function __construct(
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->filesystem = $filesystem;
    }

    /**
     * Retrieve the list of configuration files with given name that relate to specified scope
     *
     * @param string $filename
     * @param string $scope
     * @return array
     */
    public function get($filename, $scope) {
        $files = [];
        foreach($this->findInManadevModules($filename) as $module => $file) {
            $files[$module] = file_get_contents($file);
        }

        return $files;
    }

    public function findInManadevModules($filename) {
        $files = [];
        $appPath = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::APP)->getAbsolutePath();
        foreach(glob($appPath . 'code/Manadev/*/' . $filename) as $file) {
            $parts = explode('/', $file);
            $key = array_keys($parts, 'Manadev')[0];
            $module = $parts[$key] . '_' . $parts[$key + 1];
            $files[$module] = $file;
        }
        return $files;

    }
}