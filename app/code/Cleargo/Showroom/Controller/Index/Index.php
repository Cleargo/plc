<?php
/**
 * Created by Thomas
 * Date: 13/05/2016
 * Time: 5:02 PM
 */
namespace Cleargo\Showroom\Controller\Index;

class Index extends \Cleargo\Showroom\Controller\Index
{
    public function execute()
    {

        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}