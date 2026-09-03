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
use Mygento\Navigation\Model\FileInfo;
use Mygento\Navigation\Model\ResourceModel\Item\CollectionFactory;

class NavigationMenu implements ResolverInterface
{
    public function __construct(
        private MenuRepositoryInterface $repo,
        private EntityResolverPool $poolResolver,
        private FileInfo $fileInfo,
        private CollectionFactory $factory,
    ) {}

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param $context
     * @throws GraphQlNoSuchEntityException
     * @return array
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
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
        } catch (LocalizedException) {
            throw new GraphQlNoSuchEntityException(__('Navigation menu "%1" not found or disabled', $code));
        }
        $storeId = (int) $context->getExtensionAttributes()->getStore()->getId();
        $collection = $this->factory->create();
        $collection->addFieldToFilter('menu', $menu->getId());
        $collection->addFieldToFilter('is_active', 1);
        $collection->addFilter('store_id', $storeId);
        $collection->setOrder('sort_order', 'ASC');

        $idsByType = [];
        /** @var ItemInterface $item */
        foreach ($collection as $item) {
            $type = $item->getEntityType();
            $identifier = $item->getEntityIdentifier() ?? null;
            if (!$type || !$identifier) {
                continue;
            }
            $idsByType[$type][] = (string) $identifier;
        }

        $resolved = [];
        foreach ($idsByType as $type => $ids) {
            $resolver = $this->poolResolver->get($type);

            if (!$resolver) {
                continue;
            }

            $resolved[$type] = $resolver->resolveData(
                array_unique($ids),
                $storeId,
            );
        }

        /** @var ItemInterface $item */
        foreach ($collection as $item) {
            $items[] = $this->getEntity($item, $resolved);
        }

        return [
            'code' => $code,
            'items' => $items,
        ];
    }

    private function getEntity(ItemInterface $item, array $resolved = []): array
    {
        $entity = [
            'entity_identifier' => $item->getEntityIdentifier(),
            'entity_type' => $item->getEntityType(),
            'sort_order' => (int) $item->getSortOrder(),
            'name' => $item->getName(),
            'link' => $item->getEntityIdentifier(),
            'image' => $item->getImage()
                ? $this->fileInfo->getMediaUrl('mygentonav/item/' . $item->getImage())
                : null,
        ];

        if ($item->getEntityIdentifier() && isset($resolved[$item->getEntityType()])) {
            $resolvedEntity = $resolved[$item->getEntityType()][$item->getEntityIdentifier()] ?? null;

            if ($resolvedEntity) {
                $entity = array_merge($entity, $resolvedEntity);

                $entity['link'] = isset($resolvedEntity['url'])
                    ? '/' . $resolvedEntity['url']
                    : null;
            }
        }

        return $entity;
    }
}
