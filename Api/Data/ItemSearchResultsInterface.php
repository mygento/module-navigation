<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ItemSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get list of Item
     * @return \Mygento\Navigation\Api\Data\ItemInterface[]
     */
    public function getItems();

    /**
     * Set list of Item
     * @param \Mygento\Navigation\Api\Data\ItemInterface[] $items
     */
    public function setItems(array $items);
}
