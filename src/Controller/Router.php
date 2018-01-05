<?php
/**
 * Router
 *
 * @copyright Copyright © 2018 ${company}. All rights reserved.
 * @author    ${userEmail}
 */

namespace Skywire\WordpressApi\Controller;

/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Skywire License
 * that is available through the world-wide-web at this URL:
 * http://skywire.co.uk/skywire/license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to interact@skywire.co.uk so we can send you a copy immediately.
 *
 */

use Skywire\WordpressApi\Helper\RequestHelper;
use Skywire\WordpressApi\Helper\UrlHelper;
use Skywire\WordpressApi\Model\Api\Category;
use Skywire\WordpressApi\Model\Api\Post;
use Skywire\WordpressApi\Model\Api\Page;

/**
 * Description of class
 *
 * Further description is required
 *
 * @category  Skywire
 * @package   Skywire_ModuleName
 * @author    Skywire Tech Team <tech@skywire.co.uk>
 * @copyright 2017 Skywire Ltd (http://skywire.co.uk)
 * @license   Skywire Licence http://skywire.co.uk/skywire/license/
 * @link      http://skywire.co.uk
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
            $isMatch = call_user_func([$this, $method], $identifier);

            if ($isMatch) {
                $request->setModuleName('skywire_wordpress_api')
                    ->setControllerName('index')
                    ->setActionName($action);
                $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);

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

        if (!count($posts)) {
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

        if (!count($pages)) {
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

        if (!count($categories)) {
            return false;
        }

        return true;
    }
}