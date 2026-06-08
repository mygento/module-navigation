<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model\ResourceModel;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Mygento\Navigation\Api\Data\ItemInterface;

class Item extends AbstractDb
{
    public const TABLE_NAME = 'mygento_navigation_item';
    public const TABLE_PRIMARY_KEY = 'entity_id';

    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly MetadataPool $metadataPool,
        Context $context,
        ?string $connectionName = null,
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * @inheritDoc
     */
    public function getConnection()
    {
        return $this->metadataPool->getMetadata(ItemInterface::class)->getEntityConnection();
    }

    /**
     * @inheritDoc
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        return $this->entityManager->load($object, $value);
    }

    /**
     * @inheritDoc
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);

        return $this;
    }

    /**
     * Find store ids to which specified item is assigned
     */
    public function lookupStoreIds(int $id): array
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(ItemInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['es' => $this->getMainTable() . '_store'], 'store_id')
            ->join(
                ['e' => $this->getMainTable()],
                'es.entity_id = e.' . $linkField,
                [],
            )
            ->where('e.' . $entityMetadata->getIdentifierField() . ' = :entity_id');

        return $connection->fetchCol($select, ['entity_id' => (int) $id]);
    }

    public function getItemsWithTargetEntityId(int $menuId, int $storeId): array
    {
        $connection = $this->getConnection();
        $entityMetadata = $this->metadataPool->getMetadata(ItemInterface::class);
        $linkField = $entityMetadata->getLinkField();
        $select = $connection->select()->distinct()
            ->from(
                ['e' => $this->getMainTable()],
                [
                    'entity_id',
                    'entity_type',
                    'name',
                    'sort_order',
                    'image',
                    'entity_identifier',
                ],
            )
            ->joinInner(
                ['es' => $this->getMainTable() . '_store'],
                'store_id =  ' . $storeId . ' AND es.entity_id = e.' . $linkField,
                [],
            )
            ->where('e.is_active = 1')
            ->where('e.menu = ?', $menuId)
            ->order('e.sort_order ASC');

        return $connection->fetchAll($select);
    }

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::TABLE_PRIMARY_KEY);
    }
}
