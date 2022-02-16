<?php

namespace Skywire\WordpressApi\Model\Index;

use Skywire\WordpressApi\Test\Integration\Model\Api\AbstractApiTest;

class SpiderTest extends AbstractApiTest
{
    /**
     * @magentoAppIsolation                enabled
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    public function testRun()
    {
        /** @var Spider $spider */
        $spider = $this->objectManager->get(Spider::class);

        $paths = $spider->getPaths();

        $this->assertGreaterThan(0, count($paths));

        // randomise the paths and check a subset
        shuffle($paths);
        foreach (array_slice($paths, 0, 10) as $path) {
            $this->assertIsString($path->getType());
            $this->assertIsString($path->getPath());
            $this->assertNotEmpty($path->getType());
            $this->assertNotEmpty($path->getPath());
        }
    }
}
