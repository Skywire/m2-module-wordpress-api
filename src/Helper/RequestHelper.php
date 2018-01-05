<?php
/**
 * Data
 *
 * @copyright Copyright © 2018 ${company}. All rights reserved.
 * @author    ${userEmail}
 */

namespace Skywire\WordpressApi\Helper;

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
 * @package   Skywire_ModuleName
 * @author    Skywire Tech Team <tech@skywire.co.uk>
 * @copyright 2017 Skywire Ltd (http://skywire.co.uk)
 * @license   Skywire Licence http://skywire.co.uk/skywire/license/
 * @link      http://skywire.co.uk
 */
class RequestHelper
{
    /**
     * Return the Wordpress slug from a request
     *
     * @param \Magento\Framework\App\RequestInterface $request
     *
     * @return string
     */
    public function getSlug(\Magento\Framework\App\RequestInterface $request)
    {
        $urlParts   = explode('/', trim($request->getPathInfo(), '/'));
        return array_pop($urlParts);
    }
}