<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Api\Data;

interface ItemInterface
{
    public const ENTITY_ID = 'entity_id';
    public const MENU = 'menu';
    public const IS_ACTIVE = 'is_active';
    public const NAME = 'name';
    public const ENTITY_TYPE = 'entity_type';
    public const ENTITY_SLUG = 'entity_slug';
    public const SORT_ORDER = 'sort_order';
    public const STORE_ID = 'store_id';

    /**
     * Get entity id
     */
    public function getEntityId(): ?int;

    /**
     * Set entity id
     * @param int $entityId
     */
    public function setEntityId($entityId): self;

    /**
     * Get menu
     */
    public function getMenu(): ?int;

    /**
     * Set menu
     */
    public function setMenu(?int $menu): self;

    /**
     * Is active
     */
    public function isActive(): bool;

    /**
     * Set active
     */
    public function setActive(bool $isActive): self;

    /**
     * Get name
     */
    public function getName(): string;

    /**
     * Set name
     */
    public function setName(string $name): self;

    /**
     * Get entity type
     */
    public function getEntityType(): string;

    /**
     * Set entity type
     */
    public function setEntityType(string $entityType): self;

    /**
     * Get entity slug
     */
    public function getEntitySlug(): ?string;

    /**
     * Set entity slug
     */
    public function setEntitySlug(?string $entitySlug): self;

    /**
     * Get sort order
     */
    public function getSortOrder(): int;

    /**
     * Set sort order
     */
    public function setSortOrder(int $sortOrder): self;

    /**
     * Get store id
     */
    public function getStoreId(): ?array;

    /**
     * Set store id
     */
    public function setStoreId(?array $storeId): self;

    /**
     * Get ID
     */
    public function getId(): ?int;

    /**
     * Set ID
     * @param int $id
     */
    public function setId($id): self;
}
