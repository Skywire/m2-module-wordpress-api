<?php

namespace Skywire\WordpressApi\Model\Index;

use Skywire\TestFramework\Integration\TestCase;
use Skywire\WordpressApi\Test\Integration\Model\Api\AbstractApiTest;

class SpiderTest extends AbstractApiTest
{
    public function testRun()
    {
        /** @var Spider $spider */
        $spider = $this->objectManager->get(Spider::class);

        $paths = $spider->getPaths();

        $this->assertGreaterThan(0, count($paths));

        // randomise the paths and check a subset
        shuffle($paths);
        foreach (array_slice($paths, 0, 10) as $path) {
            $this->assertInternalType('string', $path->getType());
            $this->assertInternalType('string', $path->getPath());
            $this->assertNotEmpty($path->getType());
            $this->assertNotEmpty($path->getPath());
        }
    }
}
