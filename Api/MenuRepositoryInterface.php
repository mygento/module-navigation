<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface MenuRepositoryInterface
{
    /**
     * Save Menu
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\MenuInterface $entity): Data\MenuInterface;

    /**
     * Retrieve Menu
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById(int $entityId): Data\MenuInterface;

    /**
     * Retrieve menu by code
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Mygento\Navigation\Api\Data\MenuInterface
     */
    public function getByCode(string $code): Data\MenuInterface;

    /**
     * Retrieve Menu entities matching the specified criteria
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): Data\MenuSearchResultsInterface;

    /**
     * Delete Menu
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\MenuInterface $entity): bool;

    /**
     * Delete Menu
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $entityId): bool;
}
