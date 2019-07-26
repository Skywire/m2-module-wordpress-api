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
class CacheTest extends AbstractApiTest
{
    /** @magentoAppIsolation enabled */
    public function testGetCollection()
    {
        $category = $this->objectManager->get(Category::class);
        $result   = $category->getCollection();
        $cached   = $category->getCollection();

        $this->assertEquals($result, $cached);
    }

    protected function setUp()
    {
        $manager = $this->objectManager->get(\Magento\Framework\App\Cache\Manager::class);
        $manager->setEnabled(['skywire_wordpress_api'], true);

        parent::setUp();
    }

    protected function tearDown()
    {
        $manager = $this->objectManager->get(\Magento\Framework\App\Cache\Manager::class);
        $manager->setEnabled(['skywire_wordpress_api'], false);

        parent::tearDown();
    }


}