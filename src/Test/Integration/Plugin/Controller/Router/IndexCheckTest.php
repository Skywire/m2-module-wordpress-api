<?php

namespace Skywire\WordpressApi\Plugin\Controller\Router;

use Magento\Framework\App\Request\Http as Request;
use Skywire\TestFramework\Integration\TestCase;
use Skywire\WordpressApi\Controller\Router;

class IndexCheckTest extends TestCase
{

    /**
     * @magentoDataFixture populateIndex
     */
    public function testIndexHit()
    {
        /** @var IndexCheck $plugin */
        $plugin = $this->objectManager->create(IndexCheck::class);

        $callable = function () {
            return 'called';
        };

        $requestMock = $this->getMockBuilder(Request::class)->disableOriginalConstructor()
            ->setMethods(['getPathInfo'])->getMock();

        $requestMock->method('getPathInfo')->willReturn('http://fakedomain.com/blog/sample-page');

        $matched = $plugin->aroundMatch($this->objectManager->get(Router::class), $callable, $requestMock);
        $this->assertEquals('called', $matched);
    }

    public function testIndexMiss()
    {
        /** @var IndexCheck $plugin */
        $plugin = $this->objectManager->create(IndexCheck::class);

        $callable = function () {
            return 'called';
        };

        $requestMock = $this->getMockBuilder(Request::class)->disableOriginalConstructor()
            ->setMethods(['getPathInfo'])->getMock();

        $requestMock->method('getPathInfo')->willReturn('http://fakedomain.com/blog/foo');

        $matched = $plugin->aroundMatch($this->objectManager->get(Router::class), $callable, $requestMock);
        $this->assertFalse($matched);
    }

    public static function populateIndex()
    {
        require realpath(__DIR__ . '/../../../_files/populate_index.php');
    }
}
