<?php


namespace Skywire\WordpressApi\Api\Index;


use Magento\Framework\Exception\NoSuchEntityException;
use Skywire\WordpressApi\Api\Data\Index\PathInterface;

interface PathRepositoryInterface
{

    /**
     * @param string $path
     *
     * @return PathInterface
     * @throws NoSuchEntityException
     */
    public function getByPath(string $path): PathInterface;

    public function slugExists(string $slug, string $type = null): bool;

    public function pathExists(string $path): bool;

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