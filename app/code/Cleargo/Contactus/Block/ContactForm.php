<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 18/5/2016
 * Time: 11:55 AM
 */
namespace Cleargo\Contactus\Block;
use Magento\Framework\View\Element\Template;

class ContactForm extends \Magento\Contact\Block\ContactForm
{
    protected $locationFactory;

    protected $_catalogSession;

    public function __construct(
        Template\Context $context,
        \Magento\Catalog\Model\Session $catalogSession,
        \Cleargo\Contactus\Model\ResourceModel\Grid\CollectionFactory $locationFactory,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->_catalogSession = $catalogSession;
        $this->_isScopePrivate = true;
        $this->locationFactory = $locationFactory;
    }

    public function getDefaultLocation(){
        $collection = $this->locationFactory->create();
        $collection->setOrder('sort_order','ASC');
        $locationArr = $this->getLocationArray();

        foreach ($collection as $ttt){
            $location = $ttt->get();
            if(in_array($location["location_id"],$locationArr)){
                $coordinates = $location["xcoordinates"].','.$location["ycoordinates"];
                echo $coordinates;
                break;
            }
        }

    }
    public function getDefaultDescription(){
        $locationArr = $this->getLocationArray();
        $collection = $this->locationFactory->create();
        $collection->setOrder('sort_order','ASC');


        foreach ($collection as $ttt){
            $location = $ttt->get();
            if(in_array($location["location_id"],$locationArr)){
                break;
            }
        }
        return $location;
    }

    function getLocationArray(){
        $objectManager =   \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
        $resultOfStores = $connection->fetchAll("SELECT * FROM contactus_map_location_store");
        $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');

        $locationArr =[];
        foreach ($resultOfStores as $store){// $storeManager->getStore()->getId()
            if($store["store_id"] == 0 || $store["store_id"] == $storeManager->getStore()->getId()){
                $locationArr[] =  $store["location_id"];
            }
        }
        return $locationArr;
    }

    public function showLocationButtons(){
        $locationArr = $this->getLocationArray();

        $collection = $this->locationFactory->create();
        $collection->setOrder('sort_order','ASC');
        $check = 0;
        foreach ($collection as $ttt){
            $location = $ttt->get();
            if(in_array($location["location_id"],$locationArr)){
                $coordinates = $location["xcoordinates"].','.$location["ycoordinates"];

                echo '<div style="display: inline-block; 
                                        margin-right: 19px;
                                        margin-bottom: 19px;
                                        ">';
                echo "<span class='location-btn";
                if($check == 0){
                    echo ' active';
                    $check++;
                }
                echo "' style=\"margin-right:15px;\" onclick='changeCenterAndMarker( this, new google.maps.LatLng(";
                echo $coordinates;
                echo '),';
                echo json_encode($location);
                echo ")'>";

                echo $location["title"];
                echo '</span>';
                echo '</div>';
            }
        }
    }

    public function setIncCapData($key, $value)
    {
        return $this->_catalogSession->setData($key, $value);
    }

    public function getIncCapData($key, $remove = false)
    {
        return $this->_catalogSession->getData($key, $remove);
    }
}