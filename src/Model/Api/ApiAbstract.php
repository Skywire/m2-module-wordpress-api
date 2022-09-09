<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */


namespace Skywire\WordpressApi\Model\Api;


use GuzzleHttp\Psr7\Message;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Skywire\WordpressApi\Model\Data\Collection;
use Skywire\WordpressApi\Model\Data\CollectionFactory;

/**
 * @package     Skywire\WordpressApi\Model\Api
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
abstract class ApiAbstract
{
    /** @var  \GuzzleHttp\Client */
    protected $restClient;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Cache
     */
    private $cache;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CollectionFactory $collectionFactory,
        Cache $cache,
        StoreManagerInterface $storeManager,
        $restClient = null
    ) {
        $this->scopeConfig       = $scopeConfig;
        $this->collectionFactory = $collectionFactory;
        $this->cache             = $cache;
        $this->storeManager      = $storeManager;

        if ($restClient) {
            $this->restClient = $restClient;
        }
    }

    /**
     * @param array $params
     *
     * @return Collection
     */
    public function getCollection($params = array())
    {
        $response = $this->_request($this->_parseRoute(), $params);

        $collection = $this->_createCollection(\Zend_Json::decode($response->getBody()));

        if ($totalPages = $response->getHeader('X-WP-TotalPages')) {
            if (is_array($totalPages)) {
                $totalPages = $totalPages[0];
            }
            $collection->setLastPageNumber($totalPages);
        }

        return $collection;
    }

    /**
     * @param string|int $id
     *
     * @return DataObject
     */
    public function getEntity($id)
    {
        $result = $this->_request($this->_parseRoute($id));
        $entity = new DataObject();
        $this->_populateApiData(\Zend_Json::decode($result->getBody()), $entity);

        return $entity;
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->scopeConfig->getValue('skywire_wordpress_api/api/base_url', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param array $entityData
     *
     * @return Collection
     */
    protected function _createCollection(array $entityData)
    {
        $collection = $this->collectionFactory->create();
        foreach ($entityData as $data) {
            $entity = new DataObject();
            $this->_populateApiData($data, $entity);
            $collection->addItem($entity);
        }

        return $collection;
    }

    protected function _populateApiData(array $data, $targetObject)
    {
        if(!$data) {
            return $targetObject;
        }
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = $this->_populateApiData($value, new DataObject());
            }
            $key = strtolower($key);
            $targetObject->setData($key, $value);
        }

        return $targetObject;
    }

    /**
     * @param string $id
     *
     * @return string
     */
    protected abstract function _getRoute($id = '');

    /**
     * @param string|int $id
     *
     * @return string
     */
    protected function _parseRoute($id = '')
    {
        $apiPath  = $this->scopeConfig->getValue('skywire_wordpress_api/api/path', ScopeInterface::SCOPE_STORE) ?? '';
        $apiPath = trim($apiPath, '/');
        $route   = str_replace(':id', $id, $this->_getRoute());
        $route   = trim($route, '/');

        return $apiPath . '/' . $route;
    }

    /**
     * @param string $route
     * @param array  $params
     *
     * @return \GuzzleHttp\Psr7\Response|\Psr\Http\Message\ResponseInterface
     * @throws ApiException
     */
    protected function _request($route, $params = [])
    {
        $cacheKey = $this->_getCacheKey($route, $params, $this->storeManager->getStore()->getCode());
        $cached   = $this->cache->load($cacheKey);
        if ($cached) {
            return Message::parseResponse($cached);
        }

        $client = $this->getRestClient();

        try {
            $response = $client->get($route, ['query' => $params]);
        } catch (\Throwable $e) {
            throw new ApiException($e->getMessage(), $e->getCode(), $e);
        }

        $this->cache->save(Message::toString($response), $cacheKey, [], $this->scopeConfig->getValue('skywire_wordpress_api/api/cache_ttl'));

        return $response;
    }

    protected function _getCacheKey($route, $params, $storeCode)
    {
        $key = $route;
        // phpcs:disable
        $key .= md5(serialize($params));
        // phpcs:enable
        $key .= $storeCode;

        return $key;
    }

    public function resetRestClient() {
        if ($this->restClient) {
            $this->restClient = null;
        }
        return $this;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getRestClient()
    {
        if (!$this->restClient) {

            $client = new \GuzzleHttp\Client([
                'base_uri' => $this->getBaseUri(),
                'timeout'  => 10,
                'defaults' => [
                    'headers' => ['Content-Type' => 'application/json']
                ],
                'auth'    => [
                    $this->scopeConfig->getValue('skywire_wordpress_api/api/username', ScopeInterface::SCOPE_STORE),
                    $this->scopeConfig->getValue('skywire_wordpress_api/api/password', ScopeInterface::SCOPE_STORE)
                ]
            ]);

            $this->restClient = $client;
        }

        return $this->restClient;
    }
}
