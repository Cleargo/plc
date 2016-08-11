<?php
namespace Manadev\Core\Model\Feature\Config;

class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * @param mixed $dom
     * @return array
     */
    public function convert($dom)
    {
        $extractedData = [];

        $featureAttributeList = [
            'version',
            'module',
            'disable_if_dependent_features_are_disabled',
            'title',
        ];

        $extensionAttributeList = [
            'module',
            'title'
        ];

        $removeExtensionAttributeList = [
            'extension',
            'module',
        ];

        $xpath = new \DOMXPath($dom);
        $nodeList = $xpath->query('/config/*');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $item = [];
            $node = $nodeList->item($i);
            $item['type'] = $node->tagName;
            if($item['type'] == 'feature') {
                foreach ($featureAttributeList as $name) {
                    if ($node->hasAttribute($name)) {
                        $item[$name] = $node->getAttribute($name);
                    }
                }
            } else {
                foreach ($extensionAttributeList as $name) {
                    if ($node->hasAttribute($name)) {
                        $item[$name] = $node->getAttribute($name);
                    }
                }

            }
            $extractedData[] = $item;
        }

        $nodeList = $xpath->query('/config/extension/removes');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $item = [];
            $node = $nodeList->item($i);
            $item['type'] = 'removeExtension';
            foreach ($removeExtensionAttributeList as $name) {
                if ($node->hasAttribute($name)) {
                    $item[$name] = $node->getAttribute($name);
                }
            }
            $extractedData[] = $item;
        }

        return $extractedData;
    }
}
