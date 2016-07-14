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
            'no_of_new_pdf',
            'hidden',
            ['name' => 'no_of_new_pdf', 'label' => __('Current Pdf'), 'title' => __('Current Pdf')]
        );
        $fieldset->addField(
            'pdf_button',
            '\Cleargo\ProductPdf\Data\Form\Element\PdfButton',
            ['name' => 'pdf_button', 'label' => __(' Pdf Button'), 'title' => __('Pdf Button')]
        );
        foreach ($productPdf as $pdf){
            /*$fieldset->addField(
                'pdf_id_'.$pdf['pdf_id'],
                'hidden',
                ['name' => 'old_pdf['.$pdf['pdf_id'].'][]', 'label' => __('Current Pdf'), 'title' => __('Current Pdf'), 'value' => $pdf['pdf_id']]
            );*/
            $fieldset->addField(
                'old_pdf_path_'.$pdf['pdf_id'],
                '\Cleargo\ProductPdf\Data\Form\Element\Pdf',
                ['name' => 'old_pdf['.$pdf['pdf_id'].']', 'label' => __('Pdf Upload'), 'title' => __('Pdf Upload'), 'value' => $pdf['pdf_path'],
                    'note' => 'Allow type: pdf']
            );
        }

        if($productPdf){

               // $form->setValues($productPdf);

        }
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
