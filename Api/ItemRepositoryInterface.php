<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ItemRepositoryInterface
{
    /**
     * Save Item
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\ItemInterface $entity): Data\ItemInterface;

    /**
     * Retrieve Item
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById(int $entityId): Data\ItemInterface;

    /**
     * Retrieve Item entities matching the specified criteria
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): Data\ItemSearchResultsInterface;

    /**
     * Delete Item
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\ItemInterface $entity): bool;

    /**
     * Delete Item
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $entityId): bool;
}
