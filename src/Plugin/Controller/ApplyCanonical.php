<?php

/**
 * Skywire
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @package     ${NAMESPACE}
 * @author      Skywire Core Team
 * @copyright   Copyright (c) 2020 Skywire (http://www.skywire.co.uk)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
declare(strict_types=1);

namespace Skywire\WordpressApi\Plugin\Controller;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Page\Config;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Skywire\WordpressApi\Controller\AbstractAction;
use Skywire\WordpressApi\Helper\RequestHelper;
use Magento\Framework\UrlInterface;

/**
 * Class ApplyCanonical
 */
class ApplyCanonical
{
    /**
     * @var RequestHelper
     */
    protected $requestHelper;

    /**
     * @var Config
     */
    protected $pageConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;

    public function __construct(RequestHelper $requestHelper, Config $pageConfig, StoreManagerInterface $storeManager, ScopeConfigInterface $scopeConfig, UrlInterface $urlBuilder)
    {
        $this->requestHelper = $requestHelper;
        $this->pageConfig    = $pageConfig;
        $this->storeManager  = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->urlBuilder = $urlBuilder;
    }

    public function afterDispatch(AbstractAction $subject, $result)
    {
        if ($this->scopeConfig->getValue('skywire_wordpress_api/seo/enable_canonical')) {
            $slug = $this->requestHelper->getSlug($subject->getRequest());
            $subDir = $this->scopeConfig->getValue(
                'skywire_wordpress_api/api/sub_dir',
                ScopeInterface::SCOPE_STORE
            );

            if ($slug) {
                foreach ($this->pageConfig->getAssetCollection()->getAll() as $asset) {
                    if ($asset->getContentType() == 'canonical') {
                        $this->pageConfig->getAssetCollection()->remove($asset->getUrl());
                    }
                }
                $this->pageConfig->addRemotePageAsset(
                    $this->urlBuilder->getUrl($subDir . '/' . $slug),
                    'canonical',
                    ['attributes' => ['rel' => 'canonical']]
                );
            }
        }

        return $result;
    }
}
