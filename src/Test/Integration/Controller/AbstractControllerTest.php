<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Test\Integration\Controller;

/**
 * @package     Skywire\WordpressApi\Test\Integration\Controller
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
abstract class AbstractControllerTest extends \Magento\TestFramework\TestCase\AbstractController
{
    protected function setUp()
    {
        parent::setUp();

        $config = $this->_objectManager->get(\Magento\Config\Model\ResourceModel\Config::class);
        $config->saveConfig('skywire_wordpress_api/api/path', null, 'default');
        $config->saveConfig('skywire_wordpress_api/api/base_url', 'http://localhost:3000', 'default');
    }
}