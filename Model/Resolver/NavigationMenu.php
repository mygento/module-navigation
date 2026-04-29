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
use Mygento\Navigation\Model\ResourceModel\Item;

class NavigationMenu implements ResolverInterface
{
    private array $dataBuilders = [];

    public function __construct(
        private MenuRepositoryInterface $menuRepository,
        private Item $itemResource,
        array $dataBuilders = [],
    ) {
        $this->dataBuilders = $dataBuilders;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     *
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
        } catch (LocalizedException $e) {
            throw new GraphQlNoSuchEntityException(__('Product menu "%1" not found or disabled', $code));
        }
        $storeId = (int) $context->getExtensionAttributes()->getStore()->getId();
        $targetEntityIds = [];
        $itemsByType = [];
        $itemsData = $this->itemResource->getItemsWithTargetEntityId((int) $menu->getId(), $storeId);
        foreach ($itemsData as $item) {
            $targetEntityIds[$item['entity_type']][$item['target_entity_id']] = $item['target_entity_id'];
            $itemsByType[$item['entity_type']][] = $item;
        }
        $preparedItems = $this->prepareItems($itemsByType, $targetEntityIds);

        return [
            'code' => $code,
            'items' => $preparedItems,
        ];
    }

    private function prepareItems(array $itemsByType, array $targetEntityIds): array
    {
        $preparedItems = [];
        foreach ($itemsByType as $type => $items) {
            $dataBuilder = $this->dataBuilders[$type] ?? null;
            if ($dataBuilder && method_exists($dataBuilder, 'addData')) {
                $items = $dataBuilder->addData($items, $targetEntityIds[$type]);
            }
            $preparedItems = array_merge($preparedItems, $items);
        }

        return $preparedItems;
    }
}
