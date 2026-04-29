<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model\ResourceModel\Menu;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mygento\Navigation\Model\Menu;
use Mygento\Navigation\Model\ResourceModel\Menu as MenuResource;

class Collection extends AbstractCollection
{
    /** @var string */
    protected $_idFieldName = MenuResource::TABLE_PRIMARY_KEY;

    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(
            Menu::class,
            MenuResource::class,
        );
    }
}
