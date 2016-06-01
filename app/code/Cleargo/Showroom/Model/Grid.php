<?php


namespace Cleargo\Showroom\Model;

use Cleargo\Showroom\Api\Data\GridInterface;

class Grid extends \Magento\Framework\Model\AbstractModel
{
    const GRID_ID = 'entity_id'; // We define the id fieldname
    const DEPARTMENT_ID = 'entity_id'; // We define the id fieldname

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'showroom'; // parent value is 'core_abstract'

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'grid'; // parent value is 'object'

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = self::GRID_ID;

    protected function _construct()
    {
        $this->_init('Cleargo\Showroom\Model\ResourceModel\Grid');
    }


}