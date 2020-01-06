<?php

namespace Skywire\WordpressApi\Model\Index;

use Skywire\WordpressApi\Api\Index\PathRepositoryInterface;
use Skywire\WordpressApi\Model\ResourceModel\Index\Path as Resource;

class Indexer
{
    /**
     * @var Resource
     */
    protected $resource;

    /**
     * @var PathRepositoryInterface
     */
    private $repository;

    /**
     * @var Spider
     */
    private $spider;

    public function __construct(PathRepositoryInterface $repository, Spider $spider, Resource $resource)
    {
        $this->repository = $repository;
        $this->spider     = $spider;
        $this->resource = $resource;
    }

    public function collectPaths(): void
    {
        $paths = $this->spider->getPaths();

        $this->resource->getConnection()->delete($this->resource->getMainTable());

        foreach ($paths as $path) {
            $this->repository->create($path);
        }
    }
}
