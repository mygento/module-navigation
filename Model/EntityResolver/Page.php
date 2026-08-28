<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model\EntityResolver;

use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;
use Mygento\Navigation\Api\EntityResolverInterface;

class Page implements EntityResolverInterface
{
    public function __construct(
        private CollectionFactory $factory,
    ) {}

    public function resolveName(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $collection = $this->factory->create();
        $collection->addFieldToFilter(
            'page_id',
            ['in' => $ids],
        );
        $collection->addFieldToSelect(['page_id', 'title']);

        $result = [];

        foreach ($collection as $page) {
            $result[(string) $page->getId()] = sprintf(
                '[Page ID: %s] %s',
                $page->getId(),
                $page->getTitle(),
            );
        }

        return $result;
    }

    /**
     * @param string[] $ids
     * @return array<string, string>
     */
    public function resolveUrl(array $ids): array
    {
        return [];
    }
}
