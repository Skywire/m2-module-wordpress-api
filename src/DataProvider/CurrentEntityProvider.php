<?php

/**
 * Copyright Skywire. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author      Skywire Core Team
 * @copyright   Copyright (c) 2022 Skywire (http://www.skywire.co.uk)
 */
declare(strict_types=1);

namespace Skywire\WordpressApi\DataProvider;

use Magento\Framework\DataObject;
use Skywire\WordpressApi\Api\DataProvider\CurrentEntityProviderInterface;

class CurrentEntityProvider extends DataObject implements CurrentEntityProviderInterface
{
    public function setCurrentPage(?DataObject $page): CurrentEntityProviderInterface
    {
        return $this->setData(self::CURRENT_PAGE, $page);
    }

    public function setCurrentPost(?DataObject $post): CurrentEntityProviderInterface
    {
        return $this->setData(self::CURRENT_POST, $post);
    }

    public function setCurrentCategory(?DataObject $category): CurrentEntityProviderInterface
    {
        return $this->setData(self::CURRENT_CATEGORY, $category);
    }

    public function getCurrentPage(): ?DataObject
    {
        return $this->getData(self::CURRENT_PAGE);
    }

    public function getCurrentPost(): ?DataObject
    {
        return $this->getData(self::CURRENT_POST);
    }

    public function getCurrentCategory(): ?DataObject
    {
        return $this->getData(self::CURRENT_CATEGORY);
    }
}
