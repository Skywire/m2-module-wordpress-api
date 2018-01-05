<?php

namespace Skywire\WordpressApi\Model\Api;

class Page
    extends ApiAbstract
{
    const ROUTE = '/pages/:id';

    protected function _getRoute($id = '')
    {
        return self::ROUTE;
    }
}
