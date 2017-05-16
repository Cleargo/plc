<?php
/**
 * CLI Command to Reindex URLS
 *
 *
 * @author RAVALITERA Pol <pol.ravalitera@gmail.com>
 * @author Tjitse <@github>
 * @author kanduvisla <@github>
 * @author miro91 <@github>
 * @version 0.0.1
 */

namespace Afg\Core\Console\UrlRewriting;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;

/**
 * Class RegenerateUrls.php
 */
class RegenerateUrls extends Command
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\UrlRewrite\Model\UrlRewriteFactory
     */
    protected $urlRewriteFactory;

    /**
     * @var CategoryUrlRewriteGenerator
     */
    protected $categoryUrlRewriteGenerator;

    /**
     * @var UrlPersistInterface
     */
    protected $urlPersist;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var \Magento\CatalogUrlRewrite\Observer\UrlRewriteHandler
     */
    protected $urlRewriteHandler;


    /**
     * RegenerateUrls constructor.
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param string $name
     */
    public function __construct(
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator $categoryUrlRewriteGenerator,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        UrlPersistInterface $urlPersist,
        \Magento\CatalogUrlRewrite\Observer\UrlRewriteHandler $urlRewriteHandler,
        $name = 'regenerate_urls'
    )
    {
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->storeManager = $storeManager;

        $this->categoryUrlRewriteGenerator = $categoryUrlRewriteGenerator;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->urlPersist = $urlPersist;
        $this->urlRewriteHandler = $urlRewriteHandler;

        parent::__construct($name);
    }

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('afg:rewrite_url:category2');
        $this->setDescription('Regenerate Url\'s for categories');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if ($this->storeManager->isSingleStoreMode()) {
            $stores = [$this->storeManager->getStore(0)];
        } else {
            $stores = $this->storeManager->getStores();
        }

        $start_time = microtime(true);
        foreach ($stores as $store) {
            $output->writeln($store->getCode().'...');
            $categories = $this->categoryCollectionFactory->create()->setStore($store->getId())
                ->addAttributeToSelect(array('url_key', 'url_path', 'is_anchor'))
                ->addAttributeToFilter('level', 2)
                ->addAttributeToFilter('parent_id',$store->getRootCategoryId())
            ;
            foreach ($categories as $category) {
                if ($category->getUrlKey()) {
                    $category->setStoreId($store->getId());
                    $urlRewrites = array_merge(
                        $this->categoryUrlRewriteGenerator->generate($category, true)
                        , $this->urlRewriteHandler->generateProductUrlRewrites($category)
                    );
                    $this->urlPersist->replace($urlRewrites);
                }
            }
        }
        $output->writeln('[DONE] ' . round(microtime(true) - $start_time,2) .' sec');
    }

}