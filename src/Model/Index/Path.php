<?php

namespace Skywire\WordpressApi\Model\Index;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Skywire\WordpressApi\Api\Data\Index\PathInterface;

/**
 * @method \Skywire\WordpressApi\Model\ResourceModel\Index\Path getResource()
 * @method \Skywire\WordpressApi\Model\ResourceModel\Index\Path\Collection getCollection()
 * @method string getType();
 * @method self setType(string $type);
 * @method string getPath();
 * @method self setPath(string $path);
 * @method string getSlug();
 * @method self setSlug(string $slug);* 
 */
class Path extends AbstractModel implements PathInterface, IdentityInterface
{
    const CACHE_TAG = 'skywire_wordpressapi_path';

    protected $_cacheTag = 'skywire_wordpressapi_path';

    protected $_eventPrefix = 'skywire_wordpressapi_path';

    protected function _construct()
    {
        $this->_init('Skywire\WordpressApi\Model\ResourceModel\Index\Path');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}