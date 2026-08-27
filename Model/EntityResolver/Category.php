<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model\EntityResolver;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Mygento\Navigation\Api\EntityResolverInterface;

class Category implements EntityResolverInterface
{
    public function __construct(
        private CollectionFactory $factory,
    ) {}

    /**
     * @param string[] $ids
     * @return array<string, string>
     */
    public function resolve(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $collection = $this->factory->create();
        $collection->setStoreId(0);
        $collection->addAttributeToSelect('name');
        $collection->addFieldToFilter('entity_id', ['in' => $ids]);

        $result = [];

        foreach ($collection as $category) {
            $result[(string) $category->getId()] = '[' . $category->getId() . '] ' . $category->getName();
        }

        return $result;
    }
}
