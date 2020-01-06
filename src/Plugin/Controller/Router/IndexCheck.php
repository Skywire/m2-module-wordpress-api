<?php

namespace Skywire\WordpressApi\Plugin\Controller\Router;

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

    public function __construct(RequestHelper $requestHelper, PathRepositoryInterface $repository)
    {
        $this->requestHelper = $requestHelper;
        $this->repository    = $repository;
    }

    public function aroundMatch(
        \Skywire\WordpressApi\Controller\Router $subject,
        callable $proceed,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $slug = $this->requestHelper->getSlug($request);

        if (!$this->repository->slugExists($slug)) {
            return false;
        }

         return $proceed($request);
    }
}
