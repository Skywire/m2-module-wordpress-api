<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Block\Post;

use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Skywire\WordpressApi\Block\Post;
use Skywire\WordpressApi\Model\Api\Media;
use Skywire\WordpressApi\Model\Api\Tags;
use Skywire\WordpressApi\Model\Api\Post as PostApi;

/**
 * @package     Skywire\WordpressApi\Block\Post
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
class PrevNext extends Post
{
    /**
     * @var PostApi
     */
    protected $postApi;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        Media $mediaApi,
        Tags $tagsApi,
        PostApi $postApi,
        array $data = []
    ) {
        parent::__construct($context, $registry, $mediaApi, $tagsApi, $data);
        $this->postApi = $postApi;
    }

    /**
     * @return DataObject|bool
     */
    public function getPrevious()
    {
        $post     = $this->getPost();
        $postDate = $post->getDate();

        $previous = $this->postApi->getCollection(['before' => $postDate]);

        if (!count($previous)) {
            return false;
        }

        $previous = $previous->getItems();

        return array_pop($previous);
    }

    public function getNext()
    {
        $post     = $this->getPost();
        $postDate = $post->getDate();

        $next = $this->postApi->getCollection(['after' => $postDate]);

        if (!count($next)) {
            return false;
        }

        return $next->getFirstItem();
    }
}
