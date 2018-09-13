<?php

namespace Skywire\WordpressApi\Model\Api;


use Skywire\WordpressApi\Test\Integration\Model\Api\AbstractApiTest;

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