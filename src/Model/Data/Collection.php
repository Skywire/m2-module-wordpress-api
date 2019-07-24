<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Model\Data;

/**
 * @package     Skywire\WordpressApi\Model\Data
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
class Collection
    extends \Magento\Framework\Data\Collection
{
    protected $lastPageNumber;

    public function getLastPageNumber()
    {
        return $this->lastPageNumber;
    }

    /**
     * @param mixed $lastPageNumber
     */
    public function setLastPageNumber($lastPageNumber)
    {
        $this->lastPageNumber = $lastPageNumber;

        return $this;
    }
}