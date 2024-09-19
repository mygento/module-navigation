<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class EntityType implements OptionSourceInterface
{
    public function __construct(private array $types = [])
    {
        
    }

    public function toOptionArray(): array
    {
        $result = [];
        foreach ($this->types as $code => $type) {
            $result[] = ['value' => $code, 'label' => $type];
        }
        return $result;
    }
}
