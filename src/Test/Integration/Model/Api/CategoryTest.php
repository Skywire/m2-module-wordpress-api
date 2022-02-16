<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Model\Api;

use Skywire\WordpressApi\Test\Integration\Model\Api\AbstractApiTest;

/**+
 * @package     Skywire\WordpressApi\Model\Api
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
class CategoryTest extends AbstractApiTest
{
    /**
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    public function testGetCollection()
    {
        $category = $this->objectManager->get(Category::class);
        $result   = $category->getCollection();

        $this->assertCount(3, $result);
        $this->assertEquals(2, $result->getFirstItem()->getId());
    }

    /**
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    public function testGetEntity()
    {
        $category = $this->objectManager->get(Category::class);
        $result   = $category->getEntity(2);

        $this->assertEquals(2, $result->getId());
    }
}