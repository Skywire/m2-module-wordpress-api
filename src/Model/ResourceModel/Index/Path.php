<?php

namespace Skywire\WordpressApi\Model\ResourceModel\Index;

class Path extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('skywire_wordpressapi_path', 'entity_id');
    }

}