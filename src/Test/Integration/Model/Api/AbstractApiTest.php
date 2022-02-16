<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Test\Integration\Model\Api;

/**
 * @package     Skywire\WordpressApi\Test\Integration\Model\Api
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
abstract class AbstractApiTest extends \Skywire\TestFramework\Integration\TestCase
{
    /**
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    protected function setUp(): void
    {
        parent::setUp();

        $config = $this->objectManager->get(\Magento\Config\Model\ResourceModel\Config::class);
        $config->saveConfig('skywire_wordpress_api/api/path', null, 'default');
        $config->saveConfig('skywire_wordpress_api/api/base_url', 'http://localhost:3000', 'default');
    }
}