<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Api\Data;

interface MenuInterface
{
    public const ENTITY_ID = 'entity_id';
    public const CODE = 'code';

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
     * Get code
     */
    public function getCode(): string;

    /**
     * Set code
     */
    public function setCode(string $code): self;

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
