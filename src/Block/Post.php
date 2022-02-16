<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Block;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use Skywire\WordpressApi\Api\DataProvider\CurrentEntityProviderInterface;
use Skywire\WordpressApi\Model\Api\Media;
use Skywire\WordpressApi\Model\Api\Tags;

/**
 * @package     Skywire\WordpressApi\Block
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
class Post
    extends \Magento\Framework\View\Element\Template
{
    protected CurrentEntityProviderInterface $currentEntityProvider;

    /**
     * @var Media
     */
    private $mediaApi;

    /**
     * @var Tags
     */
    private $tagsApi;

    public function __construct(
        Template\Context $context,
        CurrentEntityProviderInterface $currentEntityProvider,
        Media $mediaApi,
        Tags $tagsApi,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->mediaApi = $mediaApi;
        $this->tagsApi = $tagsApi;
        $this->currentEntityProvider = $currentEntityProvider;
    }

    /**
     * override to add in breadcrumbs
     * @return mixed
     */
    protected function _prepareLayout()
    {
        if ($post = $this->getPost()) {
            if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $title = $post->getTitle()->getRendered();
                $breadcrumbsBlock->addCrumb(
                    'home',
                    [
                        'label' => __('Home'),
                        'title' => __('Home'),
                        'link'  => $this->_storeManager->getStore()->getBaseUrl()
                    ]
                );
                $breadcrumbsBlock->addCrumb(
                    'blog',
                    [
                        'label' => __($this->_scopeConfig->getValue('skywire_wordpress_api/api/nav_name',
                            ScopeInterface::SCOPE_STORE)),
                        'title' => __($this->_scopeConfig->getValue('skywire_wordpress_api/api/nav_name',
                            ScopeInterface::SCOPE_STORE)),
                        'link'  => $this->getUrl($this->_scopeConfig->getValue('skywire_wordpress_api/api/sub_dir',
                            ScopeInterface::SCOPE_STORE))
                    ]
                );
                $breadcrumbsBlock->addCrumb(
                    'post',
                    [
                        'label' => __($title),
                        'title' => __($title)
                    ]
                );
            }
        }

        return parent::_prepareLayout();
    }

    /** @return DataObject */
    public function getPost()
    {
        if ($this->hasData('post')) {
            return $this->getData('post');
        }

        return $this->currentEntityProvider->getCurrentPost();
    }

    /**
     * @param DataObject|null $post
     *
     * @return string
     */
    public function getPostUrl(DataObject $post = null)
    {
        if (!$post) {
            $post = $this->getPost();
        }

        return $this->getUrl($this->_scopeConfig->getValue('skywire_wordpress_api/api/sub_dir',
                ScopeInterface::SCOPE_STORE) . '/' . $post->getSlug());
    }

    public function getFeaturedImage()
    {
        $post = $this->getPost();

        if (!$post->getFeaturedMedia()) {
            $this->setData('featured_image', '');
        } else {
            $media = $this->mediaApi->getEntity($post->getFeaturedMedia());
            $this->setData('featured_image', $media->getSourceUrl());
        }

        return $this->getData('featured_image');
    }

    public function getTags()
    {
        $post = $this->getPost();
        $tags = array();

        if($tagIds = $post->getTags()->getData()) {
            foreach ($tagIds as $tagId) {
                $tags[] = $this->tagsApi->getEntity($tagId)->getName();
            }
        }

        return $tags;
    }
}
