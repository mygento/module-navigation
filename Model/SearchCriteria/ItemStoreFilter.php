<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model\SearchCriteria;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Mygento\Navigation\Api\Data\ItemInterface;

class ItemStoreFilter implements CustomFilterInterface
{
    /**
     * @inheritDoc
     */
    public function apply(Filter $filter, AbstractDb $collection): bool
    {
        $collection->addFilter(
            ItemInterface::STORE_ID,
            ['in' => $filter->getValue()]
        );

        return true;
    }
}
