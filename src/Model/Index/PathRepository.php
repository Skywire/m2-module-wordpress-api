<?php

namespace Skywire\WordpressApi\Model\Index;

use Magento\Framework\Exception\NoSuchEntityException;
use Skywire\WordpressApi\Api\Data\Index\PathInterface;
use Skywire\WordpressApi\Api\Index\PathRepositoryInterface;
use Skywire\WordpressApi\Model\ResourceModel\Index\Path as PathResource;
use Skywire\WordpressApi\Model\ResourceModel\Index\Path\CollectionFactory;

class PathRepository implements PathRepositoryInterface
{
    /**
     * @var PathResource
     */
    private $resource;

    /**
     * @var PathFactory
     */
    private $pathFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(PathResource $resource, CollectionFactory $collectionFactory, PathFactory $pathFactory)
    {
        $this->resource          = $resource;
        $this->pathFactory       = $pathFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function getByPath(string $path): PathInterface
    {
        $model = $this->pathFactory->create();

        $this->resource->load($model, $path, 'path');

        if ($model->getId()) {
            return $model;
        }

        throw new NoSuchEntityException();
    }

    public function slugExists(string $slug, string $type = null): bool
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('slug', $slug);


        if ($type) {
            $collection->addFieldToFilter('type', $type);
        }

        return $collection->count() >= 1;
    }

    public function pathExists(string $path): bool
    {
        try {
            $this->getByPath($path);
        } catch (NoSuchEntityException $e) {
            return false;
        }

        return true;
    }

    public function create(PathInterface $path): PathInterface
    {
        $collection = $this->collectionFactory->create();

        if ($collection->getItemByColumnValue('path', $path->getPath())) {
            $model = $this->getByPath($path->getPath());
            $path->setId($model->getId());
        }

        $this->resource->save($path);

        return $path;
    }


    public function save(PathInterface $path): PathInterface
    {
        $this->resource->save($path);

        return $path;
    }
}
