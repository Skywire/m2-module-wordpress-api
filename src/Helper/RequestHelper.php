<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Helper;

/**
 * @package     Skywire\WordpressApi\Helper
 * @author      Skywire Tech Team <tech@skywire.co.uk>
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
    protected $pageParamRegex = '/\/p\/\d+/';

    public function getSlug(\Magento\Framework\App\RequestInterface $request)
    {
        $pathInfo = trim($request->getPathInfo(), '/');

        // strip page part from url because we want to get the page slug only
        $pathInfo = preg_replace($this->pageParamRegex, '', $pathInfo);

        $urlParts = explode('/', $pathInfo);

        return array_pop($urlParts);
    }

    public function getCurrentPage(\Magento\Framework\App\RequestInterface $request)
    {
        $pathInfo = trim($request->getPathInfo(), '/');

        $matches = [];
        $matched = preg_match($this->pageParamRegex, $pathInfo, $matches);
        if ($matched) {
            $parts = explode('/', $matches[0]);

            return array_pop($parts);
        }

        return false;
    }

    public function getUrlWithoutPagination(\Magento\Framework\App\RequestInterface $request)
    {
        $pathInfo = trim($request->getPathInfo(), '/');

        // strip page part from url because we want to get the page slug only
        $pathInfo = preg_replace($this->pageParamRegex, '', $pathInfo);

        return $pathInfo;
    }
}