<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model;

use Magento\Framework\Model\AbstractModel;
use Mygento\Navigation\Api\Data\ItemInterface;

class Item extends AbstractModel implements ItemInterface
{
    /** @inheritDoc */
    protected $_eventPrefix = 'mygento_navigation_item';

    /**
     * Get entity id
     */
    public function getEntityId(): ?int
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set entity id
     * @param int $entityId
     */
    public function setEntityId($entityId): self
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get menu
     */
    public function getMenu(): ?int
    {
        return $this->getData(self::MENU);
    }

    /**
     * Set menu
     */
    public function setMenu(?int $menu): self
    {
        return $this->setData(self::MENU, $menu);
    }

    /**
     * Is active
     */
    public function isActive(): bool
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set active
     */
    public function setActive(bool $isActive): self
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Get name
     */
    public function getName(): string
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set name
     */
    public function setName(string $name): self
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get entity type
     */
    public function getEntityType(): string
    {
        return $this->getData(self::ENTITY_TYPE);
    }

    /**
     * Set entity type
     */
    public function setEntityType(string $entityType): self
    {
        return $this->setData(self::ENTITY_TYPE, $entityType);
    }

    /**
     * Get entity slug
     */
    public function getEntitySlug(): ?string
    {
        return $this->getData(self::ENTITY_SLUG);
    }

    /**
     * Set entity slug
     */
    public function setEntitySlug(?string $entitySlug): self
    {
        return $this->setData(self::ENTITY_SLUG, $entitySlug);
    }

    /**
     * Get sort order
     */
    public function getSortOrder(): int
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * Set sort order
     */
    public function setSortOrder(int $sortOrder): self
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * Get store id
     */
    public function getStoreId(): ?array
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Set store id
     */
    public function setStoreId(?array $storeId): self
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Get ID
     */
    public function getId(): ?int
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set ID
     * @param int $id
     */
    public function setId($id): self
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Item::class);
    }
}
