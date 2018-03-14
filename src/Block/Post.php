<?php

namespace Skywire\WordpressApi\Block;

use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Skywire\WordpressApi\Model\Api\Media;

class Post
    extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Media
     */
    private $mediaApi;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        Media $mediaApi,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->mediaApi = $mediaApi;
    }

    /** @return DataObject */
    public function getPost()
    {
        if ($this->hasData('post')) {
            return $this->getData('post');
        }

        return $this->registry->registry('current_post');
    }

    /**
     * @return string
     */
    public function getPostUrl()
    {
        return $this->getUrl($this->getPost()->getSlug());
    }

    public function getFeaturedImage()
    {
        $post = $this->getPost();

        if (!$post->getFeaturedMedia()) {
            $this->setData('featured_image', '');
        } else {
            $media = $this->mediaApi->getEntity($post->getFeaturedMedia());
            $this->setData('featured_image', $media->getSourceUrl());
        }

        return $this->getData('featured_image');
    }
}
