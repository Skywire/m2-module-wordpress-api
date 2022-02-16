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
class Post
    extends AbstractAction
{
    /**
     * @var \Skywire\WordpressApi\Model\Api\Media
     */
    protected $mediaApi;

    /**
     * @var \Skywire\WordpressApi\Model\Api\Tags
     */
    protected $tagsApi;

    protected CurrentEntityProviderInterface $currentEntityProvider;

    /**
     * @var RequestHelper
     */
    private $requestHelper;

    /**
     * @var \Skywire\WordpressApi\Model\Api\Post
     */
    private $postApi;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        RequestHelper $requestHelper,
        CurrentEntityProviderInterface $currentEntityProvider,
        \Skywire\WordpressApi\Model\Api\Post $postApi,
        \Skywire\WordpressApi\Model\Api\Media $mediaApi,
        \Skywire\WordpressApi\Model\Api\Tags $tagsApi
    ) {
        parent::__construct($context, $resultPageFactory);
        $this->requestHelper         = $requestHelper;
        $this->postApi               = $postApi;
        $this->mediaApi              = $mediaApi;
        $this->tagsApi               = $tagsApi;
        $this->currentEntityProvider = $currentEntityProvider;
    }

    public function execute()
    {
        $identifier = $this->requestHelper->getSlug($this->getRequest());
        $collection = $this->postApi->getCollection(['slug' => $identifier]);
        if (count($collection)) {
            $post = $collection->getFirstItem();
            $this->currentEntityProvider->setCurrentPost($post);

            $resultPage = $this->_resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set($post->getTitle()->getRendered());
            $resultPage->getConfig()->setDescription(strip_tags(substr($post->getContent()->getRendered(), 0, 400)));
            $resultPage->getConfig()->setMetadata('og:image', $this->getFeaturedImage($post));

            return $resultPage;
        }
    }

    protected function getFeaturedImage($post)
    {
        if ($post->getFeaturedMedia()) {
            $media = $this->mediaApi->getEntity($post->getFeaturedMedia());

            return $media->getSourceUrl();
        }

        return '';
    }

    public function getTags()
    {
        $post = $this->getPost();
        $tags = [];

        if ($tagIds = $post->getTags()->getData()) {
            foreach ($tagIds as $tagId) {
                $tags[] = $this->tagsApi->getEntity($tagId)->getName();
            }
        }

        return $tags;
    }
}
