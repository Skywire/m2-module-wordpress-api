<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 *
 * See LICENSE.txt for license details.
 *
 */

namespace Skywire\WordpressApi\Block\Category;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Skywire\WordpressApi\Block\Category;
use Skywire\WordpressApi\Helper\RequestHelper;

/**
 * @package     Skywire\WordpressApi\Block\Category
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
class Pagination
    extends Category
{
    /**
     * @var RequestHelper
     */
    protected $requestHelper;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        \Skywire\WordpressApi\Model\Api\Post $postApi,
        RequestHelper $requestHelper,
        array $data = []
    ) {
        $this->requestHelper = $requestHelper;
        parent::__construct($context, $registry, $postApi, $data);
    }

    public function getPrevPageUrl()
    {
        $prevPage = $this->getCurrentPage() - 1;

        if ($prevPage <= 0) {
            return null;
        }

        return $this->getPageUrl($prevPage);
    }

    public function getNextPageUrl()
    {
        if ($this->getCurrentPage() >= $this->getPosts()->getLastPageNumber()) {
            return null;
        }

        $nextPage = $this->getCurrentPage() + 1;

        return $this->getPageUrl($nextPage);
    }

    protected function getPageUrl($page)
    {
        return str_replace('/index', '',
            $this->getUrl($this->requestHelper->getUrlWithoutPagination($this->getRequest()), ['p' => $page]));
    }
}