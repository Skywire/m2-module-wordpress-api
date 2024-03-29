<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Block;

use DateTime;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Skywire\WordpressApi\Api\DataProvider\CurrentEntityProviderInterface;
use Skywire\WordpressApi\Helper\RequestHelper;
use Skywire\WordpressApi\Model\Api\ApiException;
use Skywire\WordpressApi\Model\Api\Category as CategoryApi;
use Skywire\WordpressApi\Model\Api\Media;
use Skywire\WordpressApi\Model\Api\Tags;
use Skywire\WordpressApi\Model\Api\Post as PostApi;

/**
 * @package     Skywire\WordpressApi\Block
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
class MegaMenu extends Post
{
    const UNCATEGORISED_ID = 1;

    protected $_template = 'Skywire_WordpressApi::mega-menu.phtml';

    /**
     * @var Post
     */
    protected $postApi;

    /**
     * @var CategoryApi
     */
    protected $categoryApi;

    /**
     * @var RequestHelper
     */
    protected $requestHelper;

    public function __construct(
        Template\Context $context,
        CurrentEntityProviderInterface $currentEntityProvider,
        Media $mediaApi,
        Tags $tagsApi,
        PostApi $postApi,
        CategoryApi $categoryApi,
        RequestHelper $requestHelper,
        array $data = []
    ) {
        parent::__construct($context, $currentEntityProvider, $mediaApi, $tagsApi, $data);
        $this->postApi       = $postApi;
        $this->categoryApi   = $categoryApi;
        $this->requestHelper = $requestHelper;
    }

    /**
     * @return DataObject[]
     */
    public function getLatest()
    {
        $now = new DateTime();
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
        $allCategories = [];
        $page          = 1;

        try {
            do {
                $categories    = $this->categoryApi->getCollection([
                    'page'    => $page,
                    'exclude' => self::UNCATEGORISED_ID
                ]);
                $allCategories += $categories->getItems();
                ++$page;
            } while ($page <= $categories->getLastPageNumber());
        } catch (ApiException $e) {
            return [];
        }

        foreach ($allCategories as $category) {
            $children = $this->categoryApi->getCollection(
                [
                    'parent'    => $category->getId(),
                    'page_size' => 100,
                    'exclude'   => self::UNCATEGORISED_ID
                ]);
            $category->setChildren($children);
        }

        $topLevel = array_filter(
            $allCategories,
            function ($item) {
                return $item->getParent() == 0;
            }
        );

        return $topLevel;
    }

    /**
     * Get the title of the current category, if not in a category return 'Latest Posts'
     *
     * @return string
     */
    public function getTitle()
    {
        $category = $this->getCurrentCategory();
        if ($category) {
            return $category->getName();
        }

        return 'Latest Posts';
    }

    /**
     * @return bool|DataObject
     */
    public function getCurrentCategory()
    {
        if ($this->getRequest()->getActionName() !== 'category') {
            return false;
        }

        $slug = $this->requestHelper->getSlug($this->getRequest());

        $category = $this->categoryApi->getCollection(['slug' => $slug])->getFirstItem();

        if ($category && $category->getId()) {
            return $category;
        }

        return false;
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
