<?php

namespace Skywire\WordpressApi\Block\Category;

/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Skywire License
 * that is available through the world-wide-web at this URL:
 * http://skywire.co.uk/skywire/license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to interact@skywire.co.uk so we can send you a copy immediately.
 *
 */

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Skywire\WordpressApi\Block\Category;
use Skywire\WordpressApi\Helper\RequestHelper;

/**
 * Description of class
 *
 * Further description is required
 *
 * @category  Skywire
 * @package   Skywire\ModuleName
 * @author    Skywire Tech Team <tech@skywire.co.uk>
 * @copyright 2018 Skywire Ltd (http://skywire.co.uk)
 * @license   Skywire Licence http://skywire.co.uk/skywire/license/
 * @link      http://skywire.co.uk
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
        return str_replace('/index', '', $this->getUrl($this->requestHelper->getUrlWithoutPagination($this->getRequest()), ['p' => $page]));
    }
}