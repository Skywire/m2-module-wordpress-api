<?php

namespace Skywire\WordpressApi\Block;

use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Skywire\WordpressApi\Model\Api\Media;

class Sibling
    extends Post
{
    /**
     * Limit number of siblings to display
     *
     * @var int
     */
    protected $limit = 5;

    /**
     * @var \Skywire\WordpressApi\Model\Api\Post
     */
    private $postApi;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        Media $mediaApi,
        \Skywire\WordpressApi\Model\Api\Post $postApi,
        array $data = []
    ) {
        parent::__construct($context, $registry, $mediaApi, $data);
        $this->postApi = $postApi;
    }

    /**
     * Get other posts from the same category
     *
     * @return array|\Magento\Framework\Data\Collection
     */
    public function getSiblingPosts()
    {
        $post       = $this->getPost();
        $categories = $post->getCategories()->getData();
        if (!count($categories)) {
            return [];
        }
        $firstCat = array_shift($categories);
        $siblings = $this->postApi->getCollection([
            'categories' => $firstCat,
        ]);

        $siblings = array_filter($siblings->getItems(), function ($sibling) use ($post) {
            return $sibling->getId() != $post->getId();
        });

        $siblings = array_slice($siblings, 0, $this->getLimit());

        return $siblings;
    }

    /** @return string */
    public function renderPost($post)
    {
        return $this->getChildBlock('skywire_wordpressapi.post.renderer')->setPost($post)->toHtml();
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }


}
