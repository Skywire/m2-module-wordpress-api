<?php
namespace Skywire\WordpressApi\Model\Api;

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
 * @copyright 2014 Skywire Ltd (http://skywire.co.uk)
 * @license   Skywire Licence http://skywire.co.uk/skywire/license/
 * @link      http://skywire.co.uk
 */
class Cache
    extends \Magento\Framework\Cache\Frontend\Decorator\TagScope
{
    /**
     * Cache type code unique among all cache types
     */
    const TYPE_IDENTIFIER = 'skywire_wordpress_api';
    /**
     * Cache tag used to distinguish the cache type from all other cache
     */
    const CACHE_TAG = 'SKYWIRE_WORDPRESS_API';

    /**
     * @param \Magento\Framework\App\Cache\Type\FrontendPool $cacheFrontendPool
     */
    public function __construct(\Magento\Framework\App\Cache\Type\FrontendPool $cacheFrontendPool)
    {
        parent::__construct($cacheFrontendPool->get(self::TYPE_IDENTIFIER), self::CACHE_TAG);
    }
}