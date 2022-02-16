<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Model\Api;

use Skywire\WordpressApi\Test\Integration\Model\Api\AbstractApiTest;

/**
 * @package     Skywire\WordpressApi\Model\Api
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
class PageTest extends AbstractApiTest
{
    /**
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    public function testGetCollection()
    {
        $page   = $this->objectManager->get(Page::class);
        $result = $page->getCollection();

        $this->assertCount(10, $result);
        $this->assertEquals(2, $result->getFirstItem()->getId());
    }

    /**
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    public function testGetEntity()
    {
        $page   = $this->objectManager->get(Page::class);
        $result = $page->getEntity(2);

        $this->assertEquals(2, $result->getId());
    }
}
