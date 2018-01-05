<?php

namespace Skywire\WordpressApi\Model\Api;

class Category
    extends ApiAbstract
{
    const ROUTE = '/categories/:id';

    protected function _getRoute($id = '')
    {
        return self::ROUTE;
    }
}
