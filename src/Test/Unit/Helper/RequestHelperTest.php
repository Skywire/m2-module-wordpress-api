<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 *
 * See LICENSE.txt for license details.
 *
 */

namespace Skywire\WordpressApi\Helper;

use Magento\Framework\App\Request\Http;
use Skywire\TestFramework\Unit\TestCase;

/**
 * Description of class
 *
 * @package     Skywire\WordpressApi\Helper
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
class RequestHelperTest extends TestCase
{
    /** @var RequestHelper */
    protected $helper;

    public function testGetSlug()
    {
        /** @var Http $request */
        $request = $this->objectManager->getObject(Http::class);
        $request->setPathInfo('localhost/path/to/index');
        $this->assertEquals('index', $this->helper->getSlug($request));
    }

    public function testGetCurrentPage()
    {
        $request = $this->objectManager->getObject(Http::class);
        $request->setPathInfo('localhost/path/to/index/p/3');

        $this->assertEquals(3, $this->helper->getCurrentPage($request));

        $request = $this->objectManager->getObject(Http::class);
        $request->setPathInfo('localhost/path/to/index');
        
        $this->assertEquals(false, $this->helper->getCurrentPage($request));
    }

    public function testGetUrlWithoutPagination()
    {
        $request = $this->objectManager->getObject(Http::class);
        $request->setPathInfo('localhost/path/to/index/p/3');

        $this->assertEquals('localhost/path/to/index', $this->helper->getUrlWithoutPagination($request));
    }

    protected function setUp()
    {
        $this->helper = $this->objectManager->getObject(RequestHelper::class);

        parent::setUp();
    }


}
