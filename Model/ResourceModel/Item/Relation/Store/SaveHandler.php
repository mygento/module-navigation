<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model\ResourceModel\Item\Relation\Store;

use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Mygento\Navigation\Api\Data\ItemInterface;
use Mygento\Navigation\Model\ResourceModel\Item;

class SaveHandler implements ExtensionInterface
{
    public function __construct(
        private readonly Item $resource,
        private readonly MetadataPool $metadataPool,
    ) {}

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        $entityMetadata = $this->metadataPool->getMetadata(ItemInterface::class);
        $linkField = $entityMetadata->getLinkField();
        $connection = $entityMetadata->getEntityConnection();
        $oldStores = $this->resource->lookupStoreIds((int) $entity->getId());
        $newStores = (array) $entity->getStoreId();
        $table = $this->resource->getTable($entityMetadata->getEntityTable() . '_store');

        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = [
                'entity_id = ?' => (int) $entity->getData($linkField),
                'store_id IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }

        $insert = array_diff($newStores, $oldStores);
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    'entity_id' => (int) $entity->getData($linkField),
                    'store_id' => (int) $storeId,
                ];
            }
            $connection->insertMultiple($table, $data);
        }

        return $entity;
    }
}
