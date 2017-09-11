<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 24/1/2017
 * Time: 12:09 PM
 */
namespace Cleargo\AdminTheme\Model\Order\Pdf;

use Magento\Rma\Model\Pdf\Rma as OriginalRma;



class Rma extends OriginalRma{
    /**
     * Set font as regular
     *
     * @param  \Zend_Pdf_Page $object
     * @param  int $size
     * @return \Zend_Pdf_Resource_Font
     */
    protected function _setFontRegular($object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithPath(
            $this->_rootDirectory->getAbsolutePath('lib/internal/LinLibertineFont/kaiu.ttf')
        );
        $object->setFont($font, $size);
        return $font;
    }

    /**
     * Set font as bold
     *
     * @param  \Zend_Pdf_Page $object
     * @param  int $size
     * @return \Zend_Pdf_Resource_Font
     */
    protected function _setFontBold($object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithPath(
            $this->_rootDirectory->getAbsolutePath('lib/internal/LinLibertineFont/kaiu.ttf')
        );
        $object->setFont($font, $size);
        return $font;
    }

    /**
     * Set font as italic
     *
     * @param  \Zend_Pdf_Page $object
     * @param  int $size
     * @return \Zend_Pdf_Resource_Font
     */
    protected function _setFontItalic($object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithPath(
            $this->_rootDirectory->getAbsolutePath('lib/internal/LinLibertineFont/kaiu.ttf')
        );
        $object->setFont($font, $size);
        return $font;
    }

    protected function _drawRmaItem($item, $page)
    {
        $productName = $this->string->split($item->getProductName(), 60, true, true);
        $productName = isset($productName[0]) ? $productName[0] : '';

        $page->drawText($productName, $this->getProductNameX(), $this->y, 'UTF-8');

        $productSku = $this->string->split($item->getProductSku(), 25);
        $productSku = isset($productSku[0]) ? $productSku[0] : '';
        $page->drawText($productSku, $this->getProductSkuX(), $this->y, 'UTF-8');

        $condition = $this->string->split($this->_getOptionAttributeStringValue($item->getCondition()), 25);
        $page->drawText($condition[0], $this->getConditionX(), $this->y, 'UTF-8');

        $resolution = $this->string->split($this->_getOptionAttributeStringValue($item->getResolution()), 25);

        if($resolution == null){
            $resolution[0] = '';
        }

        $page->drawText($resolution[0], $this->getResolutionX(), $this->y, 'UTF-8');
        $page->drawText(
            $this->_rmaData->parseQuantity($item->getQtyRequested(), $item),
            $this->getQtyRequestedX(),
            $this->y,
            'UTF-8'
        );

        $page->drawText($this->_rmaData->getQty($item), $this->getQtyX(), $this->y, 'UTF-8');

        $status = $this->string->split($item->getStatusLabel(), 25);
        $page->drawText($status[0], $this->getStatusX(), $this->y, 'UTF-8');

        $productOptions = $item->getOptions();
        if (is_array($productOptions) && !empty($productOptions)) {
            $this->_drawCustomOptions($productOptions, $page);
        }

        $this->y -= 10;
    }
}