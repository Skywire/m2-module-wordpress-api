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
class Page
    extends AbstractAction
{
    protected CurrentEntityProviderInterface $currentEntityProvider;

    /**
     * @var RequestHelper
     */
    private $requestHelper;

    /**
     * @var \Skywire\WordpressApi\Model\Api\Page
     */
    private $pageApi;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        RequestHelper $requestHelper,
        CurrentEntityProviderInterface $currentEntityProvider,
        \Skywire\WordpressApi\Model\Api\Page $pageApi
    ) {
        parent::__construct($context, $resultPageFactory);
        $this->requestHelper         = $requestHelper;
        $this->pageApi               = $pageApi;
        $this->currentEntityProvider = $currentEntityProvider;
    }

    public function execute()
    {
        $identifier = $this->requestHelper->getSlug($this->getRequest());
        $collection = $this->pageApi->getCollection(['slug' => $identifier]);
        if (count($collection)) {
            $page = $collection->getFirstItem();
            $this->currentEntityProvider->setCurrentPage($page);

            $resultPage = $this->_resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set($page->getTitle()->getRendered());

            return $resultPage;
        }
    }
}
