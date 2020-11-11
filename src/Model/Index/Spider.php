<?php

namespace Skywire\WordpressApi\Model\Index;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
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

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ApiClient $apiClient,
        PathFactory $pathFactory,
        StoreManagerInterface $storeManager,
        array $types = []
    ) {
        $this->scopeConfig  = $scopeConfig;
        $this->types        = $types;
        $this->apiClient    = $apiClient;
        $this->pathFactory  = $pathFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * Get an array of paths containing type and path slug
     *
     * @return Path[]
     */
    public function getPaths()
    {
        $paths  = [];
        $stores = $this->storeManager->getStores();

        foreach ($stores as $store) {
            $storeCode = $store->getCode();
            $this->storeManager->setCurrentStore($storeCode);

            $client = $this->apiClient->resetRestClient()->getRestClient();
            foreach ($this->types as $type) {
                $nextPage = true;
                $page     = 1;
                while ($nextPage) {
                    $apiPath  = $this->scopeConfig->getValue(
                        'skywire_wordpress_api/api/path',
                        ScopeInterface::SCOPE_STORE
                    );
                    $response = $client->get($apiPath . '/' . $type . '?per_page=100&page=' . $page);

                    $body = (string)$response->getBody();

                    if (!$body) {
                        $nextPage = false;
                        break;
                    }

                    $items = \Zend_Json::decode($body);

                    foreach ($items as $item) {
                        /** @var Path $path */
                        $paths[] = $this->pathFactory->create()->setData(
                            [
                                'type'       => $type,
                                'slug'       => $item['slug'],
                                'path'       => "$type/{$item['slug']}",
                                'store_code' => $storeCode,
                                'title'      => $item['name'] ?? $item['title']['rendered'],
                            ]
                        );
                    }

                    $headers = $response->getHeaders();

                    if (!isset($headers['X-WP-TotalPages']) || $headers['X-WP-TotalPages'][0] <= $page) {
                        $nextPage = false;
                    }
                    $page++;
                }
            }
        }

        return $paths;
    }
}
