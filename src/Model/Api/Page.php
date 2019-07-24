<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Model\Api;

/**
 * @package     Skywire\WordpressApi\Model\Api
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
class Page
    extends ApiAbstract
{
    const ROUTE = '/pages/:id';

    protected function _getRoute($id = '')
    {
        return self::ROUTE;
    }
}
