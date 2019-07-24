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
    public function testGetCollection()
    {
        $category = $this->objectManager->get(Category::class);
        $result   = $category->getCollection();

        $this->assertCount(3, $result);
        $this->assertEquals(2, $result->getFirstItem()->getId());
    }

    public function testGetEntity()
    {
        $category = $this->objectManager->get(Category::class);
        $result   = $category->getEntity(2);

        $this->assertEquals(2, $result->getId());
    }
}