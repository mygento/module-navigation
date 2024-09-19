<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model\ResourceModel\Item\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Mygento\Navigation\Api\Data\ItemInterface;
use Mygento\Navigation\Model\ResourceModel\Item;

class ReadHandler implements ExtensionInterface
{
    public function __construct(
        private readonly Item $resource,
    ) {
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        if ($entity->getId()) {
            $stores = $this->resource->lookupStoreIds((int) $entity->getId());
            $entity->setData(ItemInterface::STORE_ID, $stores);
        }

        return $entity;
    }
}
