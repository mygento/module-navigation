<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

declare(strict_types=1);

namespace Mygento\Navigation\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mygento\Navigation\Api\MenuRepositoryInterface;
use Mygento\Navigation\Model\Builder\DataBuilderInterface;
use Mygento\Navigation\Model\FileInfo;
use Mygento\Navigation\Model\ResourceModel\Item;

class NavigationMenu implements ResolverInterface
{
    private array $dataBuilders = [];

    public function __construct(
        private MenuRepositoryInterface $menuRepository,
        private Item $itemResource,
        private FileInfo $fileInfo,
        array $dataBuilders = [],
    ) {
        $this->dataBuilders = $this->prepareDataBuilders($dataBuilders);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param $context
     * @throws GraphQlNoSuchEntityException
     * @return array
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null,
    ) {
        $code = $args['code'] ?? null;

        try {
            $menu = $this->menuRepository->getByCode($code);
        } catch (LocalizedException) {
            throw new GraphQlNoSuchEntityException(__('Product menu "%1" not found or disabled', $code));
        }
        $storeId = (int) $context->getExtensionAttributes()->getStore()->getId();
        $targetEntityIds = [];
        $itemsByType = [];
        $itemsData = $this->itemResource->getItemsWithTargetEntityId((int) $menu->getId(), $storeId);
        foreach ($itemsData as $item) {
            $item['image'] = $item['image'] ? $this->fileInfo->getMediaUrl($item['image']) : null;
            $targetEntityIds[$item['entity_type']][$item['entity_identifier']] = $item['entity_identifier'];
            $itemsByType[$item['entity_type']][] = $item;
        }
        $preparedItems = $this->prepareItems($itemsByType, $targetEntityIds, $storeId);

        return [
            'code' => $code,
            'items' => $preparedItems,
        ];
    }

    private function prepareItems(array $itemsByType, array $targetEntityIds, int $storeId): array
    {
        $preparedItems = [];
        foreach ($itemsByType as $type => $items) {
            $dataBuilders = $this->dataBuilders[$type] ?? [];
            foreach ($dataBuilders as $dataBuilder) {
                if ($dataBuilder instanceof DataBuilderInterface) {
                    $items = $dataBuilder->addData($items, $targetEntityIds[$type], $storeId);
                }
            }
            $preparedItems = array_merge($preparedItems, $items);
        }

        usort($preparedItems, function ($a, $b) {
            return ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0);
        });

        return $preparedItems;
    }

    private function prepareDataBuilders(array $dataBuilders): array
    {
        $buildersByType = [];
        foreach ($dataBuilders as $builderConfig) {
            if (!isset($builderConfig['entity_type'], $builderConfig['class'], $builderConfig['sortOrder'])) {
                continue;
            }

            $buildersByType[$builderConfig['entity_type']][] = [
                'class' => $builderConfig['class'],
                'sortOrder' => $builderConfig['sortOrder'],
            ];
        }

        foreach ($buildersByType as $type => $builders) {
            usort($builders, function ($a, $b) {
                return $a['sortOrder'] <=> $b['sortOrder'];
            });

            $buildersByType[$type] = array_column($builders, 'class');
        }

        return $buildersByType;
    }
}
