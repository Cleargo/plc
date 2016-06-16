<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\ProductPdf\Block\Adminhtml\Product\Edit\Tab;

use Magento\Framework\Api\SimpleDataObjectConverter;

/**
 * Product inventory data
 */
class Pdf extends \Magento\Backend\Block\Widget\Form\Generic
{

    protected function _prepareForm()
    {
        $productPdf = $this->_coreRegistry->registry('product_pdf');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();


        $fieldset = $form->addFieldset('pdf_upload', ['legend' => __('Pdf Uploading')]);
        $fieldset->addField(
            'pdf_id',
            'hidden',
            ['name' => 'pdf_id', 'label' => __('Current Pdf'), 'title' => __('Current Pdf')]
        );
        /*$fieldset->addField(
            'pdf_path',
            'label',
            ['name' => 'pdf_path_current', 'label' => __('Current Pdf'), 'title' => __('Current Pdf')]
        );*/
        $fieldset->addField(
            'pdf_path',
            '\Cleargo\ProductPdf\Data\Form\Element\Pdf',
            ['name' => 'pdf_path', 'label' => __('Pdf Upload'), 'title' => __('Pdf Upload'),
                'note' => 'Allow type: pdf']
        );
        //var_dump($productPdf->getData());

       // var_dump($productPdf);
       // var_dump($this->getRequest()->getParam('store'));
        //die();
        if($productPdf){

                $form->setValues($productPdf);

        }
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
