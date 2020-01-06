<?php

namespace Skywire\WordpressApi\Model\Index;

use Skywire\WordpressApi\Model\Api\ApiAbstract;

class ApiClient extends ApiAbstract
{
    protected function _getRoute($id = '')
    {
        return '/';
    }
}
