<?php

namespace Skywire\WordpressApi\Block;

use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Skywire\WordpressApi\Model\Api\Media;
use Magento\Store\Model\ScopeInterface;

class Category
    extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var \Skywire\WordpressApi\Model\Api\Post
     */
    private $postApi;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        \Skywire\WordpressApi\Model\Api\Post $postApi,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->postApi  = $postApi;
    }

    /**
     * override to add in breadcrumbs
     * @return mixed
     */
    protected function _prepareLayout()
    {
        $title = $this->getCategory()->getName();
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
    public function getCategory()
    {
        return $this->registry->registry('current_category');
    }

    public function getPosts()
    {
        $category = $this->getCategory();
        $posts    = $this->postApi->getCollection([
            'categories' => $category->getId(),
        ]);
        return $posts;
    }

    /** @return string */
    public function renderPost($post)
    {
        return $this->getChildBlock('skywire_wordpressapi.post.renderer')->setPost($post)->toHtml();
    }
}
