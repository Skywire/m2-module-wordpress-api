<?php


namespace Skywire\WordpressApi\Api\Index;


use Magento\Framework\Exception\NoSuchEntityException;
use Skywire\WordpressApi\Api\Data\Index\PathInterface;

interface PathRepositoryInterface
{

    /**
     * @param string $path
     *
     * @param string $storeCode
     *
     * @return PathInterface
     */
    public function getByPath(string $path, string $storeCode = 'default'): PathInterface;

    public function slugExists(string $slug, string $storeCode = 'default', string $type = null): bool;

    public function pathExists(string $path, string $storeCode = 'default'): bool;

    /**
     * Create or update the path.
     *
     * Checks if path exists before creating a new entity
     *
     * @param PathInterface $path
     *
     * @return PathInterface
     */
    public function create(PathInterface $path): PathInterface;

    public function save(PathInterface $path): PathInterface;
}