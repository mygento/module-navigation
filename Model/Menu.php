<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model;

use Magento\Framework\Model\AbstractModel;
use Mygento\Navigation\Api\Data\MenuInterface;

class Menu extends AbstractModel implements MenuInterface
{
    /** @inheritDoc */
    protected $_eventPrefix = 'mygento_navigation_menu';

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
     * Get code
     */
    public function getCode(): string
    {
        return $this->getData(self::CODE);
    }

    /**
     * Set code
     */
    public function setCode(string $code): self
    {
        return $this->setData(self::CODE, $code);
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
        $this->_init(ResourceModel\Menu::class);
    }
}
