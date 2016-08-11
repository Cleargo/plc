<?php
namespace Manadev\Core\Model\Feature\Config;

class Dom extends \Magento\Framework\Config\Dom
{
    protected $module;

    /**
     * Build DOM with initial XML contents and specifying identifier attributes for merging
     *
     * Format of $idAttributes: array('/xpath/to/some/node' => 'id_attribute_name')
     * The path to ID attribute name should not include any attribute notations or modifiers -- only node names
     *
     * @param string $xml
     * @param \Magento\Framework\Config\ValidationStateInterface $validationState
     * @param array $idAttributes
     * @param string $typeAttributeName
     * @param string $schemaFile
     * @param string $errorFormat
     * @param $module
     */
    public function __construct(
        $xml,
        \Magento\Framework\Config\ValidationStateInterface $validationState,
        array $idAttributes = [],
        $typeAttributeName = null,
        $schemaFile = null,
        $errorFormat = self::ERROR_FORMAT_DEFAULT,
        $module
    ) {
        $this->module = $module;
        \Magento\Framework\Config\Dom::__construct($xml, $validationState, $idAttributes, $typeAttributeName, $schemaFile, $errorFormat);
    }

    /**
     * Getter for node by path
     *
     * @param string $nodePath
     * @return \DOMElement|null
     * @throws \Magento\Framework\Exception\LocalizedException an exception is possible if original document contains
     * multiple fixed nodes
     */
    protected function _getMatchedNode($nodePath)
    {
        if (!preg_match('/^\/config?$/i', $nodePath)) {
            return null;
        }
        return parent::_getMatchedNode($nodePath);
    }

    /**
     * Create DOM document based on $xml parameter
     *
     * @param string $xml
     * @return \DOMDocument
     * @throws \Magento\Framework\Config\Dom\ValidationException
     */
    protected function _initDom($xml) {
        $dom = new \DOMDocument();
        $dom->loadXML($xml);
        $dom->getElementsByTagName('feature')->item(0)->setAttribute('module', $this->module);
        if($extensionNode = $dom->getElementsByTagName('extension')->item(0)) {
            $extensionNode->setAttribute('module', $this->module);
        }
        $removesNodeList = $dom->getElementsByTagName('removes');
        for ($i = 0; $i < $removesNodeList->length; $i++) {
            $node = $removesNodeList->item($i);
            $node->setAttribute('module', $this->module);
        }
        if ($this->schema) {
            $errors = self::validateDomDocument($dom, $this->schema, $this->errorFormat);
            if (count($errors)) {
                throw new \Magento\Framework\Config\Dom\ValidationException(implode("\n", $errors));
            }
        }
        return $dom;
    }

    public function setModule($module) {
        $this->module = $module;
    }
}
