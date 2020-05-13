<?php

namespace Skywire\WordpressApi\Plugin\Controller\Router;

use Magento\Store\Model\StoreManagerInterface;
use Skywire\WordpressApi\Api\Index\PathRepositoryInterface;
use Skywire\WordpressApi\Helper\RequestHelper;

class IndexCheck
{
    /**
     * @var RequestHelper
     */
    protected $requestHelper;

    /**
     * @var PathRepositoryInterface
     */
    protected $repository;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        RequestHelper $requestHelper,
        PathRepositoryInterface $repository,
        StoreManagerInterface $storeManager
    )
    {
        $this->requestHelper = $requestHelper;
        $this->repository    = $repository;
        $this->storeManager  = $storeManager;
    }

    public function aroundMatch(
        \Skywire\WordpressApi\Controller\Router $subject,
        callable $proceed,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $slug = $this->requestHelper->getSlug($request);
        $storeCode = $this->storeManager->getStore()->getCode();

        if (!$this->repository->slugExists($slug, $storeCode)) {
            return false;
        }

         return $proceed($request);
    }
}
