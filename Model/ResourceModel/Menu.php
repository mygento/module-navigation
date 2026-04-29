<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Menu extends AbstractDb
{
    public const TABLE_NAME = 'mygento_navigation_menu';
    public const TABLE_PRIMARY_KEY = 'entity_id';

    public function loadByCode(AbstractModel $object, string $code): AbstractModel
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('code = :code');

        $data = $connection->fetchRow($select, [':code' => $code]);

        $object->setData($data);

        return $object;
    }

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::TABLE_PRIMARY_KEY);
    }
}
