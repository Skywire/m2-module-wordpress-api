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
class MediaTest extends AbstractApiTest
{
    /**
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    public function testGetCollection()
    {
        $media  = $this->objectManager->get(Media::class);
        $result = $media->getCollection();

        $this->assertCount(10, $result);
        $this->assertEquals(36, $result->getFirstItem()->getId());
    }

    /**
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    public function testGetEntity()
    {
        $media  = $this->objectManager->get(Media::class);
        $result = $media->getEntity(36);

        $this->assertEquals(36, $result->getId());
    }
}
