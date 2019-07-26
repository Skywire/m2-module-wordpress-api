<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */


namespace Skywire\WordpressApi\Controller;


use Skywire\WordpressApi\Helper\RequestHelper;
use Skywire\WordpressApi\Helper\UrlHelper;
use Skywire\WordpressApi\Model\Api\ApiException;
use Skywire\WordpressApi\Model\Api\Category;
use Skywire\WordpressApi\Model\Api\Page;
use Skywire\WordpressApi\Model\Api\Post;

/**
 * @package     Skywire\WordpressApi\Controller
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
class Router
    implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * Response
     *
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @var RequestHelper
     */
    private $requestHelper;

    /**
     * @var Post
     */
    private $postApi;

    /**
     * @var Category
     */
    private $categoryApi;

    /**
     * @var Page
     */
    private $pageApi;

    /**
     * @var array
     */
    protected $_matchers = array(
        'post'     => '_matchPost',
        'page'     => '_matchPage',
        'category' => '_matchCategory',
    );

    /**
     * @param \Magento\Framework\App\ActionFactory     $actionFactory
     * @param \Magento\Framework\App\ResponseInterface $response
     */
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        RequestHelper $requestHelper,
        Post $postApi,
        Category $categoryApi,
        Page $pageApi
    ) {
        $this->actionFactory = $actionFactory;
        $this->_response     = $response;
        $this->postApi       = $postApi;
        $this->requestHelper = $requestHelper;
        $this->categoryApi   = $categoryApi;
        $this->pageApi       = $pageApi;
    }

    /**
     * Validate and Match
     *
     * @param \Magento\Framework\App\RequestInterface $request
     *
     * @return bool
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $identifier = $this->requestHelper->getSlug($request);

        foreach ($this->_matchers as $action => $method) {
            $isMatch = false;
            try {
                $isMatch = call_user_func([$this, $method], $identifier);
            } catch (ApiException $exception) {
                // swallow the error as most likely a 404
            }

            if ($isMatch) {
                $request->setModuleName('skywire_wordpress_api')
                    ->setControllerName('index')
                    ->setActionName($action);
                $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);

                if ($page = $this->requestHelper->getCurrentPage($request)) {
                    $request->setParams(['p' => $page]);
                }

                return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);

            }
        }

        return false;
    }

    /**
     * @param string $identifier
     *
     * @return bool
     */
    protected function _matchPost($identifier)
    {
        $posts = $this->postApi->getCollection([
            'slug' => $identifier
        ]);

        if (!$posts || empty($posts)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $identifier
     *
     * @return bool
     */
    protected function _matchPage($identifier)
    {
        $pages = $this->pageApi->getCollection([
            'slug' => $identifier
        ]);

        if (!$pages || empty($pages)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $identifier
     *
     * @return bool
     */
    protected function _matchCategory($identifier)
    {
        $categories = $this->categoryApi->getCollection([
            'slug' => $identifier
        ]);

        if (!$categories || empty($categories)) {
            return false;
        }

        return true;
    }
}