<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Mygento\Navigation\Model\EntityResolverPool;

class Name extends Column
{
    public function __construct(
        private EntityResolverPool $pool,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = [],
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (empty($dataSource['data']['items'])) {
            return $dataSource;
        }

        /*
         * Collect all IDs grouped by entity type.
         *
         * Example:
         *
         * [
         *     'category' => [12, 25],
         *     'product'  => [45, 50]
         * ]
         */
        $idsByType = [];

        foreach ($dataSource['data']['items'] as $item) {
            $type = $item['entity_type'] ?? null;
            $identifier = $item['entity_identifier'] ?? null;
            if (!$type || !$identifier) {
                continue;
            }
            $idsByType[$type][] = (string) $identifier;
        }

        $resolved = [];
        foreach ($idsByType as $type => $ids) {
            $resolver = $this->pool->get($type);

            if (!$resolver) {
                continue;
            }

            $resolved[$type] = $resolver->resolve(
                array_unique($ids),
            );
        }

        // Add entity name to each grid row.
        foreach ($dataSource['data']['items'] as &$item) {
            $type = $item['entity_type'] ?? null;
            $identifier = $item['entity_identifier'] ?? null;
            if (!$type || !$identifier) {
                $item[$this->getName()] = $identifier;
                continue;
            }

            $item[$this->getName()] = $resolved[$type][(string) $identifier] ?? null;
        }

        return $dataSource;
    }
}
