<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\ProductPdf\Model;

use Cleargo\ProductPdf\Api\Data\PdfInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * CMS block model
 *
 * @method \Cleargo\ProductPdf\Model\ResourceModel\Pdf _getResource()
 * @method \Cleargo\ProductPdf\Model\ResourceModel\Pdf getResource()
 */
class Pdf extends \Magento\Framework\Model\AbstractModel implements PdfInterface, IdentityInterface
{
    /**
     * CMS block cache tag
     */
    const CACHE_TAG = 'product_pdf';

    /**
     * @var string
     */
    protected $_cacheTag = 'product_pdf';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'product_pdf';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\ProductPdf\Model\ResourceModel\Pdf');
    }

    /**
     * Prevent blocks recursion
     *
     * @return \Magento\Framework\Model\AbstractModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        $needle = 'pdf_id="' . $this->getId() . '"';
        if (false == strstr($this->getContent(), $needle)) {
            return parent::beforeSave();
        }
        throw new \Magento\Framework\Exception\LocalizedException(
            __('Make sure that static block content does not reference the block itself.')
        );
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Retrieve block id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::PDF_ID);
    }


    /**
     * Retrieve block pdf_path
     *
     * @return string
     */
    public function getPdfPath()
    {
        return $this->getData(self::PDF_PATH);
    }

    

    /**
     * Retrieve block creation time
     *
     * @return string
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Retrieve block update time
     *
     * @return string
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return PdfInterface
     */
    public function setId($id)
    {
        return $this->setData(self::PDF_ID, $id);
    }

    
    /**
     * Set pdf_path
     *
     * @param string $pdf_path
     * @return PdfInterface
     */
    public function setPdfPath($pdf_path)
    {
        return $this->setData(self::PDF_PATH, $pdf_path);
    }


    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return PdfInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return PdfInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Set is active
     *
     * @param bool|int $isActive
     * @return PdfInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Receive page store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }
}
