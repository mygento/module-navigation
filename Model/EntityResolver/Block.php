<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model\EntityResolver;

use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Model\ResourceModel\Block\Collection;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;
use Mygento\Navigation\Api\EntityResolverInterface;

class Block implements EntityResolverInterface
{
    public function __construct(
        private CollectionFactory $factory,
    ) {}

    /**
     * @param string[] $ids
     */
    public function resolveName(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $result = [];
        $collection = $this->getCollection($ids);
        foreach ($collection as $block) {
            $result[(string) $block->getId()] = sprintf(
                '[Block ID: %s] %s',
                $block->getId(),
                $block->getTitle(),
            );
        }

        return $result;
    }

    /**
     * @param string[] $ids
     * @return array<string, array{url: string|null, entity_identifier: string}>
     */
    public function resolveData(array $ids, int $storeId): array
    {
        $result = [];
        $collection = $this->getCollection($ids);
        $collection->addFieldToFilter(
            BlockInterface::IS_ACTIVE,
            1,
        );
        /** @var BlockInterface $block */
        foreach ($collection as $block) {
            $result[(string) $block->getId()] = [
                'url' => null,
                'entity_identifier' => $block->getIdentifier(),
            ];
        }

        return $result;
    }

    /**
     * @param string[] $ids
     */
    private function getCollection(array $ids): Collection
    {
        /** @var Collection $collection */
        $collection = $this->factory->create();
        $collection->addFieldToSelect([
            BlockInterface::BLOCK_ID,
            BlockInterface::TITLE,
            BlockInterface::IDENTIFIER,
        ]);
        $collection->addFieldToFilter(
            BlockInterface::BLOCK_ID,
            ['in' => $ids],
        );

        return $collection;
    }
}
