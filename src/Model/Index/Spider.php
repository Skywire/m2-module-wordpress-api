<?php

namespace Skywire\WordpressApi\Model\Index;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Skywire\WordpressApi\Model\Api\Type;

class Spider
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var array
     */
    protected $types;

    /**
     * @var ApiClient
     */
    protected $apiClient;

    /**
     * @var PathFactory
     */
    protected $pathFactory;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ApiClient $apiClient,
        PathFactory $pathFactory,
        array $types = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->types       = $types;
        $this->apiClient   = $apiClient;
        $this->pathFactory = $pathFactory;
    }

    /**
     * Get an array of paths containing type and path slug
     *
     * @return Path[]
     */
    public function getPaths()
    {
        $paths = [];

        $client = $this->apiClient->getRestClient();
        foreach ($this->types as $type) {
            $apiPath = $this->scopeConfig->getValue('skywire_wordpress_api/api/path');
            $response = $client->get($apiPath . '/' . $type);

            $body = (string)$response->getBody();

            if (!$body) {
                continue;
            }

            $items = \Zend_Json::decode($body);

            foreach ($items as $item) {
                /** @var Path $path */
                $paths[] = $this->pathFactory->create()->setData(['type' => $type, 'slug' => $item['slug'], 'path' => "$type/{$item['slug']}"]);
            }
        }

        return $paths;
    }
}
