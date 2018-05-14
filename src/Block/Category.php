<?php

namespace Skywire\WordpressApi\Block;

use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Skywire\WordpressApi\Model\Api\Media;
use Magento\Store\Model\ScopeInterface;
use Skywire\WordpressApi\Model\Data\Collection;

class Category
    extends \Magento\Framework\View\Element\Template
{
    const WP_API_MAX_PER_PAGE = 100;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var \Skywire\WordpressApi\Model\Api\Post
     */
    protected $postApi;

    /** @var Collection */
    protected $posts;

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
     *
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

        return parent::_prepareLayout();
    }

    /** @return DataObject */
    public function getCategory()
    {
        return $this->registry->registry('current_category');
    }

    public function getPosts()
    {
        if (!$this->posts) {
            $category    = $this->getCategory();
            $posts       = $this->postApi->getCollection([
                'categories' => $category->getId(),
                'per_page'   => $this->getPostsPerPage(),
                'page'       => $this->getCurrentPage()
            ]);
            $this->posts = $posts;
        }

        return $this->posts;
    }

    /** @return string */
    public function renderPost($post)
    {
        return $this->getChildBlock('skywire_wordpressapi.post.renderer')->setPost($post)->toHtml();
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->getRequest()->getParam('p', 1);
    }

    protected function getPostsPerPage()
    {
        $perPage = $this->_scopeConfig->getValue('skywire_wordpress_api/pagination/posts_per_page',
            ScopeInterface::SCOPE_STORE);

        // clamp value between 1 and 100
        $perPage = max(1, min(self::WP_API_MAX_PER_PAGE, $perPage));

        return $perPage;
    }
}
