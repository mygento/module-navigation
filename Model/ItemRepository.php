<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Mygento\Navigation\Api\Data\ItemInterface;
use Mygento\Navigation\Api\Data\ItemInterfaceFactory;
use Mygento\Navigation\Api\Data\ItemSearchResultsInterface;
use Mygento\Navigation\Api\Data\ItemSearchResultsInterfaceFactory;
use Mygento\Navigation\Api\ItemRepositoryInterface;
use Mygento\Navigation\Model\ResourceModel\Item\CollectionFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ItemRepository implements ItemRepositoryInterface
{
    public function __construct(
        private readonly ResourceModel\Item $resource,
        private readonly CollectionFactory $collectionFactory,
        private readonly ItemInterfaceFactory $entityFactory,
        private readonly ItemSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly StoreManagerInterface $storeManager,
        private readonly CollectionProcessorInterface $collectionProcessor,
    ) {}

    /**
     * @throws NoSuchEntityException
     */
    public function getById(int $entityId): ItemInterface
    {
        $entity = $this->entityFactory->create();
        $this->resource->load($entity, $entityId);
        if (!$entity->getId()) {
            throw new NoSuchEntityException(
                __('A Navigation Item with id "%1" does not exist', $entityId),
            );
        }

        return $entity;
    }

    /**
     * @throws CouldNotSaveException
     */
    public function save(ItemInterface $entity): ItemInterface
    {
        if (empty($entity->getStoreId())) {
            $entity->setStoreId([$this->storeManager->getStore()->getId()]);
        }

        try {
            $this->resource->save($entity);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the Navigation Item'),
                $exception,
            );
        }

        return $entity;
    }

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(ItemInterface $entity): bool
    {
        try {
            $this->resource->delete($entity);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __($exception->getMessage()),
            );
        }

        return true;
    }

    /**
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $entityId): bool
    {
        return $this->delete($this->getById($entityId));
    }

    public function getList(SearchCriteriaInterface $criteria): ItemSearchResultsInterface
    {
        /** @var \Mygento\Navigation\Model\ResourceModel\Item\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var ItemSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
