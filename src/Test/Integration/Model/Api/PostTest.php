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
class PostTest extends AbstractApiTest
{
    /**
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    public function testGetCollection()
    {
        $post   = $this->objectManager->get(Post::class);
        $result = $post->getCollection();

        $this->assertCount(10, $result);
        $this->assertEquals(1, $result->getFirstItem()->getId());
    }

    /**
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    public function testGetEntity()
    {
        $post   = $this->objectManager->get(Post::class);
        $result = $post->getEntity(1);

        $this->assertEquals(1, $result->getId());
    }
}
