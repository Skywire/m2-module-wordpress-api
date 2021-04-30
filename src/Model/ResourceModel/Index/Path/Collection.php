<?php

namespace Skywire\WordpressApi\Model\ResourceModel\Index\Path;

use Skywire\WordpressApi\Model\Index\Path;
use Skywire\WordpressApi\Model\ResourceModel\Index\Path as ResourcePath;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';


    protected function _construct()
    {
        $this->_init(Path::class, ResourcePath::class);
    }

}