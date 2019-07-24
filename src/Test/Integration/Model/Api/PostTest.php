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
    public function testGetCollection()
    {
        $post   = $this->objectManager->get(Post::class);
        $result = $post->getCollection();

        $this->assertCount(10, $result);
        $this->assertEquals(1, $result->getFirstItem()->getId());
    }

    public function testGetEntity()
    {
        $post   = $this->objectManager->get(Post::class);
        $result = $post->getEntity(1);

        $this->assertEquals(1, $result->getId());
    }
}