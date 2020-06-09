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
    public function testGetCollection()
    {
        $tags  = $this->objectManager->get(Tags::class);
        $result = $tags->getCollection();

        $this->assertCount(10, $result);
        $this->assertEquals(36, $result->getFirstItem()->getId());
    }

    public function testGetEntity()
    {
        $tags  = $this->objectManager->get(Tags::class);
        $result = $tags->getEntity(36);

        $this->assertEquals(36, $result->getId());
    }
}
