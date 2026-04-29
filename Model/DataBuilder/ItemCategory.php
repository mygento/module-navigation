<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

declare(strict_types=1);

namespace Mygento\Navigation\Model\DataBuilder;

use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ItemCategory
{
    public function __construct(
        private CollectionFactory $categoryCollection,
        private ScopeConfigInterface $scopeConfig,
    ) {}

    public function addData(array $itemsData, array $targetEntityIds): array
    {
        if (empty($targetEntityIds)) {
            return $itemsData;
        }
        $categories = $this->getCategories($targetEntityIds);
        if (empty($categories)) {
            return $itemsData;
        }
        foreach ($itemsData as &$item) {
            $itemCategory = $categories[$item['target_entity_id']] ?? null;
            if (!empty($itemCategory)) {
                $item['link'] = $this->getCategoryUrlKey($itemCategory->getUrlKey());
                $item['image'] = $itemCategory->getImage(); //todo check after implementation
            }
        }

        return $itemsData;
    }

    private function getCategoryUrlKey(?string $urlKey): ?string
    {
        if (!$urlKey) {
            return null;
        }
        $suffix = $this->scopeConfig->getValue('catalog/seo/category_url_suffix');

        return $urlKey . $suffix;
    }

    private function getCategories(array $ids): array
    {
        /** @var Collection $collection */
        $collection = $this->categoryCollection->create();
        $collection->addFieldToFilter('entity_id', $ids);
        $collection->addFieldToSelect(['url_key', 'image']);

        return $collection->getItems();
    }
}
