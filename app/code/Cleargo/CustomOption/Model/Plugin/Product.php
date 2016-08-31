<?php

namespace Cleargo\CustomOption\Model\Plugin;

class Product
{
    public function afterExecute(\Magento\Catalog\Controller\Adminhtml\Product\Save $subject)
    {

        $data = $subject->getRequest()->getPostValue();
        foreach ($data  ["product"]["options"] as $opt){
            $opt['img'] = '123';
        }
        /*var_dump($_FILES);
        echo '<pre>';
        var_dump( $data  ["product"]["options"]);
        die();*/
    }
}