<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Test\Integration\Controller;

/**
 * @package                            Skywire\WordpressApi\Test\Integration\Controller
 * @author                             Skywire Tech Team <tech@skywire.co.uk>
 */
abstract class AbstractControllerTest extends \Magento\TestFramework\TestCase\AbstractController
{
    public static function populateIndex()
    {
        require realpath(__DIR__ . '/../_files/populate_index.php');
    }
}