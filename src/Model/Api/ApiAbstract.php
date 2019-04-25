<?php
/**
 * Api
 *
 * @copyright Copyright © 2018 ${company}. All rights reserved.
 * @author    ${userEmail}
 */

namespace Skywire\WordpressApi\Model\Api;

/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Skywire License
 * that is available through the world-wide-web at this URL:
 * http://skywire.co.uk/skywire/license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to interact@skywire.co.uk so we can send you a copy immediately.
 *
 */

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Store\Model\ScopeInterface;
use Skywire\WordpressApi\Model\Data\Collection;
use Skywire\WordpressApi\Model\Data\CollectionFactory;
use Skywire\WordpressApi\Model\RestClientFactory;

/**
 * Description of class
 *
 * Further description is required
 *
 * @category  Skywire
 * @package   Skywire_ModuleName
 * @author    Skywire Tech Team <tech@skywire.co.uk>
 * @copyright 2017 Skywire Ltd (http://skywire.co.uk)
 * @license   Skywire Licence http://skywire.co.uk/skywire/license/
 * @link      http://skywire.co.uk
 */
abstract class ApiAbstract
{
    /** @var  \GuzzleHttp\Client */
    protected $restClient;

    /**
     * @var RestClientFactory
     */
    protected $restClientFactory;

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
        RestClientFactory $restClientFactory
    ) {
        $this->scopeConfig       = $scopeConfig;
        $this->collectionFactory = $collectionFactory;
        $this->cache             = $cache;
        $this->restClientFactory = $restClientFactory;
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

    protected function _populateApiData(array $data = [], $targetObject = null)
    {
        if (!$targetObject) {
            $targetObject = $this;
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
        $apiPath = $this->scopeConfig->getValue('skywire_wordpress_api/api/path');
        $route   = str_replace(':id', $id, $this->_getRoute());
        $route   = trim($route, '/');

        return $apiPath . '/' . $route;
    }

    /**
     * @param string $route
     * @param array  $params
     *
     * @return \Zend_Http_Response
     */
    protected function _request($route, $params = [])
    {
        $cacheKey = $this->_getCacheKey($route, $params);
        $cached   = $this->cache->load($cacheKey);
        if ($cached) {
            return unserialize($cached);
        }

        $client = $this->getRestClient();

        $response     = $client->get($route, ['query' => $params]);
        $responseBody = (string) $response->getBody();

        if ($response->getStatusCode() !== 200) {
            throw new ApiException($responseBody, $response->getStatusCode());
        }

        $this->cache->save(serialize($response), $cacheKey, [], 3600 * 24);

        return $response;
    }

    protected function _getCacheKey($route, $params)
    {
        $key = $route;
        $key .= md5(serialize($params));

        return $key;
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
                    'headers' => ['Content-Type' => 'application/json'],
                    'auth'    => [
                        $this->scopeConfig->getValue('skywire_wordpress_api/api/username', ScopeInterface::SCOPE_STORE),
                        $this->scopeConfig->getValue('skywire_wordpress_api/api/password', ScopeInterface::SCOPE_STORE)
                    ],
                ]
            ]);

            $this->restClient = $client;
        }

        return $this->restClient;
    }
}
