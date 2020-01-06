<?php

namespace Skywire\WordpressApi\Model\ResourceModel\Index\Path;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';


    protected function _construct()
    {
        $this->_init('Skywire\WordpressApi\Model\Index\Path', 'Skywire\WordpressApi\Model\ResourceModel\Index\Path');
    }

}