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
            $nextPage = true;
            $page = 1;
            while ($nextPage) {
                $apiPath = $this->scopeConfig->getValue('skywire_wordpress_api/api/path');
                $response = $client->get($apiPath . '/' . $type . '?per_page=100&page=' . $page);

                $body = (string)$response->getBody();

                if (!$body) {
                    $nextPage = false;
                    break;
                }

                $items = \Zend_Json::decode($body);

                foreach ($items as $item) {
                    /** @var Path $path */
                    $paths[] = $this->pathFactory->create()->setData(['type' => $type, 'slug' => $item['slug'], 'path' => "$type/{$item['slug']}"]);
                }

                $headers = $response->getHeaders();

                if ($headers['X-WP-TotalPages'][0] <= $page) {
                    $nextPage = false;
                }
                $page++;
            }
        }

        return $paths;
    }
}
