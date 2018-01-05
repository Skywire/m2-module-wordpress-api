<?php

namespace Skywire\WordpressApi\Model\Api;

class Media
    extends ApiAbstract
{
    const ROUTE = '/media/:id';

    protected function _getRoute($id = '')
    {
        return self::ROUTE;
    }
}
