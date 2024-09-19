<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface MenuSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get list of Menu
     * @return \Mygento\Navigation\Api\Data\MenuInterface[]
     */
    public function getItems();

    /**
     * Set list of Menu
     * @param \Mygento\Navigation\Api\Data\MenuInterface[] $items
     */
    public function setItems(array $items);
}
