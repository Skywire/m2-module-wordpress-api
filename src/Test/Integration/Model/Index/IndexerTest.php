<?php

namespace Skywire\WordpressApi\Model\Index;


use Skywire\WordpressApi\Model\ResourceModel\Index\Path\CollectionFactory;
use Skywire\WordpressApi\Test\Integration\Model\Api\AbstractApiTest;

class IndexerTest extends AbstractApiTest
{
    /** @var CollectionFactory */
    protected $collectionFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->collectionFactory = $this->objectManager->get(CollectionFactory::class);
    }

    /**
     * @magentoDbIsolation                 enabled
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/path /
     * @magentoConfigFixture               current_store skywire_wordpress_api/api/base_url http://localhost:3000
     */
    public function testCollectPaths()
    {
        /** @var Indexer $indexer */
        $indexer = $this->objectManager->get(Indexer::class);

        $indexer->collectPaths();

        $this->assertCount(23, $this->collectionFactory->create());
    }
}
