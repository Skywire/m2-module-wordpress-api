<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 *
 * See LICENSE.txt for license details.
 *
 */

/**
 * Description of class
 *
 * @package     Skywire\WordpressApi\Controller
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */

namespace Skywire\WordpressApi\Controller;


use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\Request\Http;
use Skywire\TestFramework\Unit\TestCase;
use Skywire\WordpressApi\Helper\RequestHelper;
use Skywire\WordpressApi\Model\Api\Category;
use Skywire\WordpressApi\Model\Api\Page;
use Skywire\WordpressApi\Model\Api\Post;

class RouterTest extends TestCase
{

    private $postApi;

    private $router;

    private $categoryApi;

    private $pageApi;

    private $request;

    private $actionFactory;

    private $collectionWithData;

    private $collectionWithoutData;

    public function testPostMatch()
    {
        $this->postApi->method('getCollection')->willReturn($this->collectionWithData);

        $matched = $this->router->match($this->request);

        $this->assertInstanceOf(Forward::class, $matched);
    }

    public function testPostUnmatched()
    {
        $this->postApi->method('getCollection')->willReturn($this->collectionWithoutData);

        $matched = $this->router->match($this->request);

        $this->assertFalse($matched);
    }

    public function testCategoryMatch()
    {
        $this->categoryApi->method('getCollection')->willReturn($this->collectionWithData);

        $matched = $this->router->match($this->request);

        $this->assertInstanceOf(Forward::class, $matched);
    }

    public function testCategoryUnmatched()
    {
        $this->categoryApi->method('getCollection')->willReturn($this->collectionWithoutData);

        $matched = $this->router->match($this->request);

        $this->assertFalse($matched);
    }

    public function testPageMatch()
    {
        $this->pageApi->method('getCollection')->willReturn($this->collectionWithData);

        $matched = $this->router->match($this->request);

        $this->assertInstanceOf(Forward::class, $matched);
    }

    public function testPageUnmatched()
    {
        $this->pageApi->method('getCollection')->willReturn($this->collectionWithoutData);

        $matched = $this->router->match($this->request);

        $this->assertFalse($matched);
    }

    protected function setUp(): void
    {
        $this->postApi     = $this->getMockBuilder(Post::class)->disableOriginalConstructor()->setMethods(['getCollection'])->getMock();
        $this->categoryApi = $this->getMockBuilder(Category::class)->disableOriginalConstructor()->setMethods(['getCollection'])->getMock();
        $this->pageApi     = $this->getMockBuilder(Page::class)->disableOriginalConstructor()->setMethods(['getCollection'])->getMock();
        $requestHelper     = $this->getMockBuilder(RequestHelper::class)->setMethods(['getSlug', 'getCurrentPage'])->getMock();
        $requestHelper->method('getSlug')->willReturn('slug');
        $requestHelper->method('getCurrentPage')->willReturn('1');

        $this->collectionWithData = $this->objectManager->getCollectionMock(\Skywire\WordpressApi\Model\Data\Collection::class, ['page1', 'page2']);
        $this->collectionWithData->method('getSize')->willReturn(2);
        $this->collectionWithoutData = $this->objectManager->getCollectionMock(\Skywire\WordpressApi\Model\Data\Collection::class, []);

        $this->actionFactory = $this->getMockBuilder(\Magento\Framework\App\ActionFactory::class)->disableOriginalConstructor()->setMethods(['create'])->getMock();
        $this->actionFactory->method('create')->willReturn($this->objectManager->getObject(Forward::class));

        $this->router = $this->objectManager->getObject(Router::class, [
            'postApi'       => $this->postApi,
            'categoryApi'   => $this->categoryApi,
            'pageApi'       => $this->pageApi,
            'requestHelper' => $requestHelper,
            'actionFactory' => $this->actionFactory
        ]);


        $this->request = $this->objectManager->getObject(Http::class);


        parent::setUp(); // TODO: Change the autogenerated stub
    }


}
