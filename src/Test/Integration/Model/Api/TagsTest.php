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
class TagsTest extends AbstractApiTest
{
    /**
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    public function testGetCollection()
    {
        $tags  = $this->objectManager->get(Tags::class);
        $result = $tags->getCollection();

        $this->assertCount(4, $result);
        $this->assertEquals(6, $result->getFirstItem()->getId());
    }

    /**
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    public function testGetEntity()
    {
        $tags  = $this->objectManager->get(Tags::class);
        $result = $tags->getEntity(12);

        $this->assertEquals(12, $result->getId());
    }
}
