<?php

namespace Skywire\WordpressApi\Block\Post;


use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Skywire\WordpressApi\Block\Post;
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
        PostApi $postApi,
        array $data = []
    ) {
        parent::__construct($context, $registry, $mediaApi, $data);
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