<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\ProductPdf\Api\Data;

/**
 * CMS block interface.
 * @api
 */
interface PdfInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const PDF_ID      = 'pdf_id';
    const LINKED_PRODUCT_ID      = 'linked_product_id';
    const PDF_PATH         = 'pdf_path';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getPdfPath();


    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Set ID
     *
     * @param int $id
     * @return PdfInterface
     */
    public function setId($id);


    /**
     * Set title
     *
     * @param string $title
     * @return PdfInterface
     */
    public function setPdfPath($pdfPath);


    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return PdfInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return PdfInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Set is active
     *
     * @param bool|int $isActive
     * @return PdfInterface
     */
    public function setIsActive($isActive);
}
