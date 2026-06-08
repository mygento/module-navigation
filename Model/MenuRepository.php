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
use Mygento\Navigation\Api\Data\MenuInterface;
use Mygento\Navigation\Api\Data\MenuInterfaceFactory;
use Mygento\Navigation\Api\Data\MenuSearchResultsInterface;
use Mygento\Navigation\Api\Data\MenuSearchResultsInterfaceFactory;
use Mygento\Navigation\Api\MenuRepositoryInterface;
use Mygento\Navigation\Model\ResourceModel\Menu\CollectionFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class MenuRepository implements MenuRepositoryInterface
{
    public function __construct(
        private readonly ResourceModel\Menu $resource,
        private readonly CollectionFactory $collectionFactory,
        private readonly MenuInterfaceFactory $entityFactory,
        private readonly MenuSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor,
    ) {}

    /**
     * @throws NoSuchEntityException
     */
    public function getById(int $entityId): MenuInterface
    {
        $entity = $this->entityFactory->create();
        $this->resource->load($entity, $entityId);
        if (!$entity->getId()) {
            throw new NoSuchEntityException(
                __('A Navigation Menu with id "%1" does not exist', $entityId),
            );
        }

        return $entity;
    }

    /**
     * @throws CouldNotSaveException
     */
    public function save(MenuInterface $entity): MenuInterface
    {
        try {
            $this->resource->save($entity);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the Navigation Menu'),
                $exception,
            );
        }

        return $entity;
    }

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(MenuInterface $entity): bool
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

    public function getByCode(string $code): MenuInterface
    {
        $entity = $this->entityFactory->create();
        $this->resource->loadByCode($entity, $code);
        if (!$entity->getId()) {
            throw new NoSuchEntityException(
                __('A menu with code "%1" does not exist', $code),
            );
        }

        return $entity;
    }

    public function getList(SearchCriteriaInterface $criteria): MenuSearchResultsInterface
    {
        /** @var \Mygento\Navigation\Model\ResourceModel\Menu\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var MenuSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
