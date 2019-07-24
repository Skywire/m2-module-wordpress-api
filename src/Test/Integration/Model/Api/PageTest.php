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
    public function testGetCollection()
    {
        $page   = $this->objectManager->get(Page::class);
        $result = $page->getCollection();

        $this->assertCount(10, $result);
        $this->assertEquals(2, $result->getFirstItem()->getId());
    }

    public function testGetEntity()
    {
        $page   = $this->objectManager->get(Page::class);
        $result = $page->getEntity(2);

        $this->assertEquals(2, $result->getId());
    }
}