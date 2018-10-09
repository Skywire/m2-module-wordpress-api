<?php

namespace Skywire\WordpressApi\Block;


use DateTime;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Skywire\WordpressApi\Model\Api\ApiException;
use Skywire\WordpressApi\Model\Api\Category as CategoryApi;
use Skywire\WordpressApi\Model\Api\Media;
use Skywire\WordpressApi\Model\Api\Post as PostApi;

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
class MegaMenu extends Post
{
    protected $_template = 'Skywire_WordpressApi::mega-menu.phtml';

    /**
     * @var Post
     */
    protected $postApi;

    /**
     * @var CategoryApi
     */
    protected $categoryApi;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        Media $mediaApi,
        PostApi $postApi,
        CategoryApi $categoryApi,
        array $data = []
    ) {
        parent::__construct($context, $registry, $mediaApi, $data);
        $this->postApi     = $postApi;
        $this->categoryApi = $categoryApi;
    }

    /**
     * @return DataObject[]
     */
    public function getLatest()
    {
        $now    = new DateTime();
        try {
            $latest = $this->postApi->getCollection(
                [
                    'before'   => $now->format(DateTime::ATOM),
                    'per_page' => 4, // TODO Make this configurable
                ]
            );
        } catch (ApiException $e) {
            return [];
        }

        if (!count($latest)) {
            return [];
        }

        return $latest->getItems();
    }

    /**
     * @return DataObject[]
     */
    public function getCategories()
    {
        try {
            $categories = $this->categoryApi->getCollection();
        } catch (ApiException $e) {
            return [];
        }

        foreach ($categories as $category) {
            $children = $categories->getItemsByColumnValue('parent', $category->getId());
            $category->setChildren($children);
        }

        return $categories->getItemsByColumnValue('parent', 0);
    }

    /**
     * @param DataObject $category
     *
     * @return string
     */
    public function getCategoryUrl(DataObject $category)
    {
        return $this->getUrl('*/category/' . $category->getSlug());
    }
}