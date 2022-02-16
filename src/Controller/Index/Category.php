<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Skywire\WordpressApi\Api\DataProvider\CurrentEntityProviderInterface;
use Skywire\WordpressApi\Controller\AbstractAction;
use Skywire\WordpressApi\Helper\RequestHelper;

/**
 * @package     Skywire\WordpressApi\Controller\Index
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
class Category
    extends AbstractAction
{
    protected CurrentEntityProviderInterface $currentEntityProvider;

    /**
     * @var RequestHelper
     */
    private $requestHelper;

    /**
     * @var \Skywire\WordpressApi\Model\Api\Category
     */
    private $categoryApi;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        RequestHelper $requestHelper,
        CurrentEntityProviderInterface $currentEntityProvider,
        \Skywire\WordpressApi\Model\Api\Category $categoryApi
    ) {
        parent::__construct($context, $resultPageFactory);
        $this->requestHelper         = $requestHelper;
        $this->categoryApi           = $categoryApi;
        $this->currentEntityProvider = $currentEntityProvider;
    }

    public function execute()
    {
        $identifier = $this->requestHelper->getSlug($this->getRequest());
        $collection = $this->categoryApi->getCollection(['slug' => $identifier]);
        if (count($collection)) {
            $category = $collection->getFirstItem();
            $this->currentEntityProvider->setCurrentCategory($category);

            $resultPage = $this->_resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set($category->getName());

            return $resultPage;
        }
    }
}
