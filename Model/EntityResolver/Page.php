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

    public function resolve(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        $collection = $this->factory->create();
        $collection->setStoreId(0);

        $collection->addFieldToFilter(
            'page_id',
            ['in' => $ids],
        );

        $collection->addFieldToSelect('title');

        $result = [];

        foreach ($collection as $page) {
            $result[(string) $page->getId()] = sprintf(
                '[Page ID: %d] %s',
                $page->getId(),
                $page->getTitle(),
            );
        }

        return $result;
    }
}
