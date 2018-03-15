<?php

namespace Skywire\WordpressApi\Block;

use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Skywire\WordpressApi\Model\Api\Media;
use Magento\Store\Model\ScopeInterface;

class Page
    extends \Magento\Framework\View\Element\Template
{
    /**
     * @var BreadcrumbHelper
     */
    protected $breadcrumbHelper;
    /**
     * @var Registry
     */
    private $registry;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        BreadcrumbHelper $breadcrumbHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->breadcrumbHelper = $breadcrumbHelper;
    }

    /**
     * override to add in breadcrumbs
     * @return mixed
     */
    protected function _prepareLayout()
    {
        if($post = $this->getPost()) {
            if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $title = $post->getTitle()->getRendered();
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
        }
        return parent::_prepareLayout();
    }

    /** @return DataObject */
    public function getPage()
    {
        return $this->registry->registry('current_page');
    }
}
