<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\Theme\Block\Html;



use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Catalog\Model\Category;
use Magento\Framework\Registry;

/**
 * Html page top menu block
 */
class Topmenu extends \Magento\Theme\Block\Html\Topmenu
//class Topmenu extends Template implements IdentityInterface
{
    /**
     * Cache identities
     *
     * @var array
     */
    protected $identities = [];

    /**
     * Top menu data tree
     *
     * @var \Magento\Framework\Data\Tree\Node
     */
    protected $_menu;

    /**
     * Core registry
     *
     * @var Registry
     */

    protected $current_level_zero_cms_iden;

    /**
     * @param Template\Context $context
     * @param NodeFactory $nodeFactory
     * @param TreeFactory $treeFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory,
        Category $cus_att,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $nodeFactory, $treeFactory, $data);
    }
    /**
     * Recursively generates top menu html from data that is specified in $menuTree
     *
     * @param \Magento\Framework\Data\Tree\Node $menuTree
     * @param string $childrenWrapClass
     * @param int $limit
     * @param array $colBrakes
     * @return string
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getHtml(
        \Magento\Framework\Data\Tree\Node $menuTree,
        $childrenWrapClass,
        $limit,
        $colBrakes = []
    ) {
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        $counter = 1;
        $itemPosition = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        foreach ($children as $child) {
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $child->setClass($outermostClass);
            }

            if (count($colBrakes) && $colBrakes[$counter]['colbrake']) {
                $html .= '</ul></li><li class="column"><ul>';
            }

            if($parentLevel==0 && $childLevel==1) {
                if(preg_match('/\/new-arrivals/', $child->getUrl()) ||  preg_match('/\/best-seller/', $child->getUrl())|| preg_match('/\/sale/', $child->getUrl())){
                    //get category
                    $cate_id = preg_replace("/category-node-/","",$child->getId());
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $category = $objectManager->get('Magento\Catalog\Model\Category')->load($cate_id);

                    //var_dump($category->getImageUrl());
                    //echo $this->_categoryHelper->getCategoryUrl($category);

                    $html .= '<li class="catWithImage">';
                    $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '>';
                    $html .= '<span class="image" style="background:transparent url('.$category->getImageUrl().') center no-repeat;background-size:80%;background-size:contain;"></span>';
                    $html .= '<span class="text">' . $this->escapeHtml($child->getName()) . '</span>';
                    $html .= '</a>';
                    $html .= '</li>';
                }
                else{

                    $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
                    $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml($child->getName()) . '</span></a>'
                        . $this->_addSubMenu(
                            $child,
                            $childLevel,
                            $childrenWrapClass,
                            $limit
                        ) . '</li>';
                }
            }
            else {

                $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
                $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml($child->getName()) . '</span></a>';
                $html .= $this->_addSubMenu(
                    $child,
                    $childLevel,
                    $childrenWrapClass,
                    $limit
                );
                $html .= '</li>';
            }
            $itemPosition++;
            $counter++;
        }

        if (count($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }

        return $html;
    }
}
