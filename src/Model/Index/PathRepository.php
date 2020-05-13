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

    public function getByPath(string $path, string $storeCode = 'default'): PathInterface
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('path', $path)
            ->addFieldToFilter('store_code', $storeCode);

        if ($collection->getFirstItem()->getId()) {
            return $collection->getFirstItem();
        }

        throw new NoSuchEntityException();
    }

    public function slugExists(string $slug, string $storeCode = 'default', string $type = null): bool
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('slug', $slug)
            ->addFieldToFilter('store_code', $storeCode);


        if ($type) {
            $collection->addFieldToFilter('type', $type);
        }

        return $collection->count() >= 1;
    }

    public function pathExists(string $path, string $storeCode = 'default'): bool
    {
        try {
            $this->getByPath($path, $storeCode);
        } catch (NoSuchEntityException $e) {
            return false;
        }

        return true;
    }

    public function create(PathInterface $path): PathInterface
    {
        $collection = $this->collectionFactory->create();
        $row = $collection
            ->addFieldToFilter('path', $path->getPath())
            ->addFieldToFilter('store_code', $path->getStoreCode());

        if ($row->count() > 0) {
            $model = $this->getByPath($path->getPath(), $path->getStoreCode());
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
