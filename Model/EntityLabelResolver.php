<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;

class EntityLabelResolver
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
        private BlockRepositoryInterface $blockRepository,
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function resolve(string $entityType, ?string $entityId): ?string
    {
        try {
            return match ($entityType) {
                'cms_page' => sprintf(
                    '[Page ID: %d] %s',
                    $entityId,
                    $this->pageRepository->getById($entityId)->getTitle(),
                ),
                'cms_block' => sprintf(
                    '[Block ID: %d] %s',
                    $entityId,
                    $this->blockRepository->getById($entityId)->getTitle(),
                ),
                'catalog_product' => sprintf(
                    '[Product ID: %d] %s',
                    $entityId,
                    $this->productRepository->getById($entityId)->getName(),
                ),
                default => null,
            };
        } catch (LocalizedException $e) {
            return null;
        }
    }
}
