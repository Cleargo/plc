<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Category form input image element
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Cleargo\ProductPdf\Data\Form\Element;

use Magento\Framework\UrlInterface;

class PdfButton extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    /**
     * @var UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @param \Magento\Framework\Data\Form\Element\Factory $factoryElement
     * @param \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection
     * @param \Magento\Framework\Escaper $escaper
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        UrlInterface $urlBuilder,
        $data = []
    ) {
        $this->_urlBuilder = $urlBuilder;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->setType('file');
    }

    /**
     * Return element html code
     *
     * @return string
     */
    public function getElementHtml()
    {
        $widgetButton = $this->getForm()->getParent()->getLayout();
        $buttonHtml = $widgetButton->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            ['label' => 'Add New Pdf', 'onclick' => 'addNewImg()', 'class' => 'add']
        )->toHtml();
        $html = '';
        $html .= $buttonHtml;



        $name = $this->getName();
        $parentName = parent::getName();
        $html .= <<<EndSCRIPT

        <script language="javascript">
        id = 0;

        function addNewImg(){
        id++;
        //document.getElementById('no_of_new_pdf').value(id);
        var pdfLabel = document.createElement('label');
        var t = document.createTextNode("Upload New Pdf");
        pdfLabel.appendChild(t);
         
        var nameStr = 'new_pdf[]';
        nameStr=  nameStr.replace(/%j%/g, 2).replace(/%id%/g, id);
         
        var pdfUpload = document.createElement('input');
        pdfUpload.name= nameStr;
        pdfUpload.type= 'file';
        
        var pdfInput = document.createElement('div');
        pdfInput.className= 'control';
        pdfInput.appendChild(pdfUpload);
        
        var pdfRow = document.createElement('div');
        pdfRow.className= 'field';
        pdfRow.appendChild(pdfLabel);
        pdfRow.appendChild(pdfInput);
        
        document.getElementById('pdf_upload').appendChild(pdfRow);

        // Delete button
        var new_row_button = document.createElement( 'input' );
        new_row_button.type = 'checkbox';
        new_row_button.value = 'Delete';


        // Delete function
        new_row_button.onclick= function(){
            this.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode );
    
            // Appease Safari
            //    without it Safari wants to reload the browser window
            //    which nixes your already queued uploads
            return false;
        };
    
        }
        </script>

EndSCRIPT;
        return $html;
    }

    /**
     * Return html code of delete checkbox element
     *
     * @return string
     */
    protected function _getDeleteCheckbox()
    {
        $html = '';
        if ($this->getValue()) {
            $label = (string)new \Magento\Framework\Phrase('Delete Pdf');
            $html .= '<span class="delete-pdf">';
            $html .= '<input type="checkbox"' .
                ' name="' .
                parent::getName() .
                '[delete]" value="1" class="checkbox"' .
                ' id="' .
                $this->getHtmlId() .
                '_delete"' .
                ($this->getDisabled() ? ' disabled="disabled"' : '') .
                '/>';
            $html .= '<label for="' .
                $this->getHtmlId() .
                '_delete"' .
                ($this->getDisabled() ? ' class="disabled"' : '') .
                '> ' .
                $label .
                '</label>';
            $html .= $this->_getHiddenInput();
            $html .= '</span>';
        }

        return $html;
    }

    /**
     * Return html code of hidden element
     *
     * @return string
     */
    protected function _getHiddenInput()
    {
        return '<input type="hidden" name="' . parent::getName() . '[value]" value="' . $this->getValue() . '" />';
    }

    /**
     * Get image preview url
     *
     * @return string
     */
    protected function _getUrl()
    {
        return $this->getValue();
    }

    /**
     * Return name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData('name');
    }
}
