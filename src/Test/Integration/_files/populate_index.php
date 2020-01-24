<?php

use Skywire\WordpressApi\Api\Data\Index\PathInterface;
use Skywire\WordpressApi\Api\Index\PathRepositoryInterface;

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$paths = [
    [
        'type' => 'category',
        'slug' => 'aut-architecto-nihil',
        'path' => 'category/aut-architecto-nihil',
    ],
    [
        'type' => 'page',
        'slug' => 'sample-page',
        'path' => 'page/sample-page',
    ],
    [
        'type' => 'post',
        'slug' => 'hello-world',
        'path' => 'post/hello-world',
    ],
];

foreach ($paths as $data) {
    /** @var PathInterface $path */
    $path = $objectManager->create(PathInterface::class);
    $path->setData($data);

    /** @var PathRepositoryInterface $repository */
    $repository = $objectManager->get(PathRepositoryInterface::class);

    $repository->save($path);
}
