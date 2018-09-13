<?php

namespace Skywire\WordpressApi\Test\Integration\Model\Api;


/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Skywire License
 * that is available through the world-wide-web at this URL:
 * http://skywire.co.uk/skywire/license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to interact@skywire.co.uk so we can send you a copy immediately.
 *
 */

/**
 * Description of class
 *
 * Further description is required
 *
 * @category             Skywire
 * @package              Skywire\ModuleName
 * @author               Skywire Tech Team <tech@skywire.co.uk>
 * @copyright            2018 Skywire Ltd (http://skywire.co.uk)
 * @license              Skywire Licence http://skywire.co.uk/skywire/license/
 * @link                 http://skywire.co.uk
 */
abstract class AbstractApiTest extends \Skywire\TestFramework\Integration\TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $config = $this->objectManager->get(\Magento\Config\Model\ResourceModel\Config::class);
        $config->saveConfig('skywire_wordpress_api/api/path', null, 'default');
        $config->saveConfig('skywire_wordpress_api/api/base_url', 'http://localhost:3000', 'default');
    }
}