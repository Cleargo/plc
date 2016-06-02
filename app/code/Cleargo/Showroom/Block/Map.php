<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 18/5/2016
 * Time: 11:55 AM
 */
namespace Cleargo\Showroom\Block;
use Magento\Framework\View\Element\Template;

class Map extends \Magento\Framework\View\Element\Template
{
    protected $locationFactory;

    public function __construct(
        Template\Context $context,
        \Cleargo\Showroom\Model\ResourceModel\Grid\CollectionFactory $locationFactory,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
        $this->locationFactory = $locationFactory;
    }

    public function getDefaultLocation(){
        $collection = $this->locationFactory->create();
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
        $resultOfStores = $connection->fetchAll("SELECT * FROM showroom_location_store");
        $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');

        $locationArr =[];
        foreach ($resultOfStores as $store){// $storeManager->getStore()->getId()
            if($store["store_id"] == 0 || $store["store_id"] == $storeManager->getStore()->getId()){
                $locationArr[] =  $store["location_id"];
            }
        }
        return $locationArr;
    }

    function getTypeArray($targetType){
        $objectManager =   \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
        $resultOfStores = $connection->fetchAll("SELECT * FROM showroom_location_type");
        $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');

        $locationArr =[];
        foreach ($resultOfStores as $store){// $storeManager->getStore()->getId()
            if($store["type_id"] == 0 || $store["type_id"] == $targetType){
                $locationArr[] =  $store["location_id"];
            }
        }
        return $locationArr;
    }

    function echoHeading($type,$heading,$locationArr){
        if($this->getTypeArray($type)){
            $hasType =0 ;
            foreach ($this->getTypeArray($type) as $item ){
                if(in_array($item,$locationArr)){
                    $hasType = 1;
                }
            }
            if($hasType){
                echo '<div>'.$heading.'</div>';
            }
        }
    }

    public function showLocationButtons(){
        $locationArr = $this->getLocationArray();

        $collection = $this->locationFactory->create();
        $collection->setOrder('sort_order','ASC');
        $this->echoHeading(1,'Type A Product',$locationArr);

        foreach ($collection as $ttt){
            $location = $ttt->get();
            if(in_array($location["location_id"],$locationArr)&&in_array($location["location_id"],$this->getTypeArray(1))){
                $coordinates = $location["xcoordinates"].','.$location["ycoordinates"];

                echo "<span  style=\"display:inline-block; margin-right:15px;\" onclick='changeCenterAndMarker(new google.maps.LatLng(";
                echo $coordinates;
                echo '),';
                echo json_encode($location);
                echo ")'>";

                echo $location["address"];
                echo '<br/>TEL:';
                echo $location["telephone"];
                echo '/';
                echo $location["whatsapp"];
                echo '(WHATSAPP)<br/>Business Hours:';
                echo $location["office_hour"];
                echo '</span>';
            }
        }

        $this->echoHeading(2,'Type B Product',$locationArr);
        foreach ($collection as $ttt){
            $location = $ttt->get();
            if(in_array($location["location_id"],$locationArr)&&in_array($location["location_id"],$this->getTypeArray(2))){
                $coordinates = $location["xcoordinates"].','.$location["ycoordinates"];

                echo "<span  style=\"display:inline-block; margin-right:15px;\" onclick='changeCenterAndMarker(new google.maps.LatLng(";
                echo $coordinates;
                echo '),';
                echo json_encode($location);
                echo ")'>";

                echo $location["address"];
                echo '<br/>TEL:';
                echo $location["telephone"];
                echo '/';
                echo $location["whatsapp"];
                echo '(WHATSAPP)<br/>Business Hours:';
                echo $location["office_hour"];
                echo '</span>';
            }
        }
        $this->echoHeading(3,'Type C Product',$locationArr);

        foreach ($collection as $ttt){
            $location = $ttt->get();
            if(in_array($location["location_id"],$locationArr)&&in_array($location["location_id"],$this->getTypeArray(3))){
                $coordinates = $location["xcoordinates"].','.$location["ycoordinates"];

                echo "<span  style=\"display:inline-block; margin-right:15px;\" onclick='changeCenterAndMarker(new google.maps.LatLng(";
                echo $coordinates;
                echo '),';
                echo json_encode($location);
                echo ")'>";

                echo $location["address"];
                echo '<br/>TEL:';
                echo $location["telephone"];
                echo '/';
                echo $location["whatsapp"];
                echo '(WHATSAPP)<br/>Business Hours:';
                echo $location["office_hour"];
                echo '</span>';
            }
        }
    }
}