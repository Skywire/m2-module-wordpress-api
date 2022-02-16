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
class PostTest extends AbstractControllerTest
{
    /**
     * @magentoDataFixture                 populateIndex
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    public function testExecute()
    {
        $this->dispatch('blog/hello-world');
        $response = $this->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('hello-world', $response->getBody());
    }
}
