<?php

namespace Skywire\WordpressApi\Model\Index;


use Skywire\TestFramework\Integration\TestCase;
use Skywire\WordpressApi\Model\ResourceModel\Index\Path\CollectionFactory;
use Skywire\WordpressApi\Test\Integration\Model\Api\AbstractApiTest;

class IndexerTest extends AbstractApiTest
{
    /** @var CollectionFactory */
    protected $collectionFactory;

    protected function setUp()
    {
        parent::setUp();
        $this->collectionFactory = $this->objectManager->get(CollectionFactory::class);
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testCollectPaths()
    {
        /** @var Indexer $indexer */
        $indexer = $this->objectManager->get(Indexer::class);

        $indexer->collectPaths();

        $this->assertCount(23, $this->collectionFactory->create());
    }
}
