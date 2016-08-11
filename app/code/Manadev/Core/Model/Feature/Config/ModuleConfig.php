<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Model\Feature\Config;

class ModuleConfig
{
    /**
     * @var FileResolver
     */
    private $fileResolver;
    /**
     * @var \Magento\Framework\Module\Declaration\Converter\Dom
     */
    private $converter;
    /**
     * @var \Magento\Framework\Xml\Parser
     */
    private $parser;

    /**
     * @param FileResolver $fileResolver
     * @param \Magento\Framework\Module\Declaration\Converter\Dom $converter
     * @param \Magento\Framework\Xml\Parser $parser
     */
    public function __construct(
        \Manadev\Core\Model\Feature\Config\FileResolver $fileResolver,
        \Magento\Framework\Module\Declaration\Converter\Dom $converter,
        \Magento\Framework\Xml\Parser $parser
    ) {

        $this->fileResolver = $fileResolver;
        $this->converter = $converter;
        $this->parser = $parser;
    }

    public function load() {
        $result = [];
        foreach($this->fileResolver->get('etc/*module*.xml*', 'global') as $file => $contents) {
            try {
                $this->parser->loadXML($contents);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    new \Magento\Framework\Phrase(
                        'Invalid Document: %1%2 Error: %3',
                        [$file, PHP_EOL, $e->getMessage()]
                    ),
                    $e
                );
            }

            $data = $this->converter->convert($this->parser->getDom());
            $name = key($data);
            $result[$name] = $data[$name];
        }
        return $result;
    }
}