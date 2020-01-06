<?php

namespace Skywire\WordpressApi\Test\Integration\Controller;


use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\Request\Http as Request;
use Skywire\WordpressApi\Controller\Router;
use Skywire\WordpressApi\Test\Integration\Model\Api\AbstractApiTest;

class RouterTest extends AbstractApiTest
{
    /**
     * @dataProvider dataProvider
     */
    public function testMatch($slug, $shouldMatch)
    {
        $requestMock = $this->getMockBuilder(Request::class)->disableOriginalConstructor()
            ->setMethods(['getPathInfo'])->getMock();

        $requestMock->method('getPathInfo')->willReturn('http://fakedomain.com/blog/' . $slug);

        /** @var Router $router */
        $router = $this->objectManager->create(Router::class);

        $matched = $router->match($requestMock);
        if ($shouldMatch) {
            $this->assertInstanceOf(Forward::class, $matched);
        } else {
            $this->assertFalse($matched);
        }
    }

    public function dataProvider()
    {
        return [
            'invalid-slug' => ['foo', false],
            'page'         => ['sample-page', true],
            'category'     => ['aut-architecto-nihil', true],
            'post'         => ['hello-world', true],
        ];
    }
}
