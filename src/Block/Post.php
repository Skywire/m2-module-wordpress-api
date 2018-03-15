<?php

namespace Skywire\WordpressApi\Block;

use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Skywire\WordpressApi\Model\Api\Media;
use Magento\Store\Model\ScopeInterface;

class Post
    extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Media
     */
    private $mediaApi;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        Media $mediaApi,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->mediaApi = $mediaApi;
    }

    /**
     * override to add in breadcrumbs
     * @return mixed
     */
    protected function _prepareLayout()
    {
        $title = $this->getPost()->getTitle()->getRendered();
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Home'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'blog',
                [
                    'label' => __($this->_scopeConfig->getValue('skywire_wordpress_api/api/nav_name',
                        ScopeInterface::SCOPE_STORE)),
                    'title' => __($this->_scopeConfig->getValue('skywire_wordpress_api/api/nav_name',
                        ScopeInterface::SCOPE_STORE)),
                    'link' => $this->getUrl($this->_scopeConfig->getValue('skywire_wordpress_api/api/sub_dir',
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
        return parent::_prepareLayout();
    }

    /** @return DataObject */
    public function getPost()
    {
        if ($this->hasData('post')) {
            return $this->getData('post');
        }

        return $this->registry->registry('current_post');
    }

    /**
     * @return string
     */
    public function getPostUrl()
    {
        return $this->getUrl($this->_scopeConfig->getValue('skywire_wordpress_api/api/sub_dir',
            ScopeInterface::SCOPE_STORE) . '/' . $this->getPost()->getSlug());
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
}
