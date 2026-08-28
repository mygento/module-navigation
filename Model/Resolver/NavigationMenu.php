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
use Mygento\Navigation\Api\Data\ItemInterface;
use Mygento\Navigation\Api\MenuRepositoryInterface;
use Mygento\Navigation\Model\EntityResolverPool;
use Mygento\Navigation\Model\ResourceModel\Item\CollectionFactory;

class NavigationMenu implements ResolverInterface
{
    public function __construct(
        private MenuRepositoryInterface $repo,
        private EntityResolverPool $resolver,
        private CollectionFactory $factory,
    ) {}

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

        $items = [];

        try {
            $menu = $this->repo->getByCode($code);
            $collection = $this->factory->create();
            $collection->addFieldToFilter('menu', $menu->getId());
            $collection->addFieldToFilter('is_active', 1);
            $collection->addFilter('store_id', $context->getExtensionAttributes()->getStore()->getId());
            $collection->setOrder('sort_order', 'ASC');

            /** @var ItemInterface $item */
            foreach ($collection as $item) {
                $items[] = [
                    'entity_identifier' => $item->getEntityIdentifier(),
                    'entity_type' => $item->getEntityType(),
                    'sort_order' => (int) $item->getSortOrder(),
                    'name' => $item->getName(),
                    'link' => '',
                    'image' => '',
                ];
            }
        } catch (LocalizedException) {
            throw new GraphQlNoSuchEntityException(__('Navigation1 menu2 "%1" not found or disabled', $code));
        }

        return [
            'code' => $code,
            'items' => $items,
        ];
    }
}
