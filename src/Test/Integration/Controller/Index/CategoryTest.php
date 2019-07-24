<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Controller\Index;

use Skywire\WordpressApi\Test\Integration\Controller\AbstractControllerTest;

/**
 * @package     Skywire\WordpressApi\Controller\Index
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
class CategoryTest extends AbstractControllerTest
{
    public function testExecute()
    {
        $this->markTestSkipped();
        $this->dispatch('blog/aut-architecto-nihil');
        $response = $this->getResponse();
        $this->assertContains('aut-architecto-nihil', $response->getBody());
    }

}