<?php
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

/**
 * Description of class
 *
 * Further description is required
 *
 * @category  Skywire
 * @package   Skywire\ModuleName
 * @author    Skywire Tech Team <tech@skywire.co.uk>
 * @copyright 2018 Skywire Ltd (http://skywire.co.uk)
 * @license   Skywire Licence http://skywire.co.uk/skywire/license/
 * @link      http://skywire.co.uk
 */

namespace Skywire\WordpressApi\Block;


use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Skywire\TestFramework\Integration\TestCase;
use Skywire\WordpressApi\Model\RestClientFactory;

class MegaMenuTest extends TestCase
{
    /**
     * @var \Skywire\WordpressApi\Model\Api\Category
     */
    private $categoryApi;

    /**
     * @var \Skywire\WordpressApi\Model\Api\Post
     */
    private $postApi;

    public function testGetCategories()
    {
        $block      = $this->objectManager->create(MegaMenu::class, ['categoryApi' => $this->categoryApi]);
        $categories = $block->getCategories();

        $this->assertCount(2, $categories);

        $this->assertEquals('Level 1', $categories[17]->getName());
        // TODO Fix test to properly load only the specific children, caused by change in child loading method
//        $this->assertCount(1, $categories[0]->getChildren());
//        $this->assertEquals('Level 2', $categories[0]->getChildren()[0]->getName());

        $this->assertEquals('Level 1-a', $categories[19]->getName());
        // TODO Fix test to properly load only the specific children, caused by change in child loading method
//        $this->assertCount(1, $categories[1]->getChildren());
//        $this->assertEquals('Level 2-a', $categories[1]->getChildren()[0]->getName());
    }

    public function testGetLatest()
    {
        $block = $this->objectManager->create(MegaMenu::class, ['postApi' => $this->postApi]);

        $latest = $block->getLatest();
        $this->assertCount(4, $latest);
        $this->assertEquals('post-1', $latest[1]->getSlug());
        $this->assertEquals('post-4', $latest[4]->getSlug());
    }

    public function testToHtml()
    {
        $block = $this->objectManager->create(MegaMenu::class,
            [
                'categoryApi' => $this->categoryApi,
                'postApi'     => $this->postApi
            ]
        );

        $html = $this->objectManager->get(State::class)->emulateAreaCode(Area::AREA_FRONTEND,
            function () use ($block) {
                return $block->toHtml();
            }
        );

        $actual = new \DOMDocument();
        $actual->loadHTML($html);

        $this->assertCount(4, $actual->getElementsByTagName('ul'));
        $this->assertCount(16, $actual->getElementsByTagName('li'));

    }

    protected function getCategoryData()
    {
        return file_get_contents(__DIR__ . '/../_files/category_hierarchy.json');
    }

    protected function getLatestData()
    {
        return file_get_contents(__DIR__ . '/../_files/latest_posts.json');
    }

    protected function setUp()
    {
        $this->markTestSkipped('Need to replace zend responses with guzzle mocks');
        // setup category
        $response = new \Zend_Http_Response(200, [], $this->getCategoryData());

        $restClient = $this->getMockBuilder(\GuzzleHttp\Client::class)->disableOriginalConstructor()->getMock();
        $restClient->method('get')->willReturn($response);

        $clientFactory = $this->getMockBuilder(RestClientFactory::class)->getMock();
        $clientFactory->method('create')->willReturn($restClient);

        $categoryApi = $this->objectManager->create(\Skywire\WordpressApi\Model\Api\Category::class,
            ['restClientFactory' => $clientFactory]);

        $this->categoryApi = $categoryApi;

        // setup posts
        $response = new \Zend_Http_Response(200, [], $this->getLatestData());

        $restClient = $this->getMockBuilder(\GuzzleHttp\Client::class)->disableOriginalConstructor()->getMock();
        $restClient->method('get')->willReturn($response);

        $clientFactory = $this->getMockBuilder(RestClientFactory::class)->getMock();
        $clientFactory->method('create')->willReturn($restClient);

        $postApi = $this->objectManager->create(\Skywire\WordpressApi\Model\Api\Post::class,
            ['restClientFactory' => $clientFactory]);

        $this->postApi = $postApi;
    }
}
