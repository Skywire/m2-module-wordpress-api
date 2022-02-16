<?php

/**
 * Copyright Skywire. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author      Skywire Core Team
 * @copyright   Copyright (c) 2022 Skywire (http://www.skywire.co.uk)
 */
declare(strict_types=1);

namespace Skywire\WordpressApi\Api\DataProvider;

use Magento\Framework\DataObject;

/**
 * @api
 */
interface CurrentEntityProviderInterface
{
    public const CURRENT_PAGE = 'current_page';

    public const CURRENT_POST = 'current_post';

    public const CURRENT_CATEGORY = 'current_category';

    public function setCurrentPage(?DataObject $page): CurrentEntityProviderInterface;

    public function setCurrentPost(?DataObject $post): CurrentEntityProviderInterface;

    public function setCurrentCategory(?DataObject $category): CurrentEntityProviderInterface;

    public function getCurrentPage(): ?DataObject;

    public function getCurrentPost(): ?DataObject;

    public function getCurrentCategory(): ?DataObject;
}
