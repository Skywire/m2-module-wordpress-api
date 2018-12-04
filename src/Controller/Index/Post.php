<?php

namespace Skywire\WordpressApi\Controller\Index;

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

use Magento\Framework\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Skywire\WordpressApi\Controller\AbstractAction;
use Skywire\WordpressApi\Helper\RequestHelper;

/**
 * Description of class
 *
 * Further description is required
 *
 * @category  Skywire
 * @package   Skywire_ModuleName
 * @author    Skywire Tech Team <tech@skywire.co.uk>
 * @copyright 2017 Skywire Ltd (http://skywire.co.uk)
 * @license   Skywire Licence http://skywire.co.uk/skywire/license/
 * @link      http://skywire.co.uk
 */
class Post
    extends AbstractAction
{
    /**
     * @var RequestHelper
     */
    private $requestHelper;

    /**
     * @var \Skywire\WordpressApi\Model\Api\Post
     */
    private $postApi;

    /**
     * @var Registry
     */
    private $registry;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        RequestHelper $requestHelper,
        Registry $registry,
        \Skywire\WordpressApi\Model\Api\Post $postApi
    ) {
        parent::__construct($context, $resultPageFactory);
        $this->requestHelper = $requestHelper;
        $this->postApi       = $postApi;
        $this->registry      = $registry;
    }

    public function execute()
    {
        $identifier = $this->requestHelper->getSlug($this->getRequest());
        $collection = $this->postApi->getCollection(['slug' => $identifier]);
        if (count($collection)) {
            $post = $collection->getFirstItem();
            $this->registry->register('current_post', $post);

            $resultPage = $this->_resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set($post->getTitle()->getRendered());
            $resultPage->getConfig()->setDescription($post->getExcerpt()->getRendered());

            return $resultPage;
        }
    }
}