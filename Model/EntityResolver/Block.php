<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model\EntityResolver;

use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;
use Mygento\Navigation\Api\EntityResolverInterface;

class Block implements EntityResolverInterface
{
    public function __construct(
        private CollectionFactory $factory,
    ) {}

    public function resolve(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $collection = $this->factory->create();
        $collection->setStoreId(0);

        $collection->addFieldToSelect([
            'block_id',
            'title',
        ]);

        $collection->addFieldToFilter(
            'block_id',
            ['in' => $ids],
        );

        $result = [];

        foreach ($collection as $block) {
            $result[(string) $block->getId()] = sprintf(
                '[Block ID: %d] %s',
                $block->getId(),
                $block->getTitle(),
            );
        }

        return $result;
    }
}
