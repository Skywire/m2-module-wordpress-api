<?php

namespace Skywire\WordpressApi\Model\Data;

/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Skywire License
 * that is available through the world-wide-web at this URL:
 * http://skywire.co.uk/skywire/license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to interact@skywire.co.uk so we can send you a copy immediately.
 *
 */

/**
 * Description of class
 *
 * Further description is required
 *
 * @category  Skywire
 * @package   Skywire\ModuleName
 * @author    Skywire Tech Team <tech@skywire.co.uk>
 * @copyright 2018 Skywire Ltd (http://skywire.co.uk)
 * @license   Skywire Licence http://skywire.co.uk/skywire/license/
 * @link      http://skywire.co.uk
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